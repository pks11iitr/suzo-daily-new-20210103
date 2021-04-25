<?php

namespace App\Console\Commands;

use App\Events\LogOrder;
use App\Events\OrderCancelled;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProcessCancellations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        while($detail=OrderDetail::where('cancel_raised', true)
            ->where(DB::raw('TIMESTAMPDIFF(MINUTE, raised_at, NOW())'), '>=', 4)
            ->first())
        {
            $order=Order::with('details')
                ->find($detail->order_id);
            $is_complete=true;
            foreach($order->details as $d){
                // checking if canllation of complete order,
                // or selected items are cancelled
                if($d->status!='cancelled' || ($d->status=='cancelled' && $d->cancel_raised==0) || $d->delivered_quantity > 0){
                    $is_complete=false;
                    break;
                }
            }

            if($is_complete){
                $this->cancelCompleteOrder($order);
            }else{
                $this->cancelPartialOrder($order);
            }
        }
    }

    public function cancelPartialOrder($order){
        $cancel_items=[];
        foreach($order->details as $d){
            if($d->status=='cancelled' && $d->cancel_raised){
                $cancel_items[]=$d;
            }
        }

        foreach($cancel_items as $detail){
            if($detail->type=='subscription'){

            }else{

            }
        }

    }

    public function calculateTotalAfterCancellation($order){

        //calculate total cost after cancellation
        foreach($order->details as $item) {
            if($order->details=='cancelled')
                continue;

            if($item->type=='subscription'){
                $total_cost=$total_cost+$item->quantity*($item->product->price??0)*$item->no_of_days;
                $savings=$savings+$item->quantity*(($item->product->price??0)-($item->product->cut_price))*$item->no_of_days;

                if($order->customer->membership_expiry>=$item->start_date){
                    $subscription_days=$item->days->map(function($element){
                        return $element->id;
                    })->toArray();
                    $count_free_days=calculateDaysCountBetweenDate($item->start_date, $order->customer->membership_expiry, $subscription_days);
                    $delivery_charge=$delivery_charge+($item->product->delivery_charge*$item->total_quantity)-$item->quantity*$item->product->delivery_charge*$count_free_days;
                }else{
                    $delivery_charge=$delivery_charge+($item->product->delivery_charge*$item->total_quantity);
                }

            }
            else{
                $total_cost=$total_cost+$item->quantity*($item->product->price??0);
                $savings=$savings+$item->quantity*(($item->product->price??0)-($item->product->cut_price));

                if(!isset($daywise_delivery_total))
                    $daywise_delivery_total[$item->start_date]=0;
                $daywise_delivery_total[$item->start_date]=$daywise_delivery_total[$item->start_date]+$item->product->price*$item->quantity;

            }

            $items[]=new OrderDetail(array_merge($item->only('product_id', 'quantity','type','start_date','time_slot_id','no_of_days', 'total_quantity'), ['price'=>$item->product->price, 'cut_price'=>$item->product->cut_price]));

            $days[$item->product_id]=$item->days->map(function($elem){
                return $elem->id;
            })->toArray();

        }

        if(!empty($daywise_delivery_total)){
            foreach($daywise_delivery_total as $key=>$val){
                if($order->customer->membership_expiry < $key && $val< 399){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }else if($order->customer->membership_expiry >= $key && $val< 149){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }
            }
        }
    }


    public function cancelCompleteOrder($order){
        if($order->points_used>0)
            Wallet::updatewallet($order->user_id, 'Refund for order cancellation from order id: '.$order->refid, 'Credit',$order->points_used, 'POINT', $order->id);

        $cash_return=$order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->points_used;
        if($cash_return>0)
            Wallet::updatewallet($order->user_id, 'Refund for order cancellation from order id: '.$order->refid, 'Credit',$cash_return, 'CASH', $order->id);

        $order->details()->update(['cancel_raised'=>false]);

        $order->update([
            'status'=>'cancelled',
            'total_cost'=>0,
            'savings'=>0,
            'coupon'=>null,
            'coupon_discount'=>0,
            'delivery_charge'=>0,
            'use_balance'=>false,
            'use_points'=>false,
            'balance_used'=>0,
            'points_used'=>0
        ]);

        event(new OrderCancelled($order));
        event(new LogOrder($order));
    }
}
