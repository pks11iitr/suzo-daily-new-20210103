<?php

namespace App\Console\Commands;

use App\Events\ItemCancelled;
use App\Events\LogOrder;
use App\Events\OrderCancelled;
use App\Models\Coupon;
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
                if($d->cancel_raised=='false'){
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

    private function cancelPartialOrder($order){
        $result=$this->calculateTotalAfterCancellation($order);

        $order->update([
            'total_cost'=>$result['total_cost'],
            'savings'=>$result['savings'],
            'coupon'=>$result['coupon_discount']>0?$order->coupon:null,
            'coupon_discount'=>$result['coupon_discount'],
            'delivery_charge'=>$result['delivery_charge'],
            'use_balance'=>$result['balance_used']>0,
            'use_points'=>$result['eligible_goldcash']>0,
            'balance_used'=>$result['balance_used'],
            'points_used'=>$result['eligible_goldcash']
        ]);

        $order->details()->update(['cancel_raised'=>false]);

        if($result['points_refund'])
            Wallet::updatewallet($order->user_id, 'Refund for order cancellation from order id: '.$order->refid, 'Credit',$result['points_refund'], 'POINT', $order->id);

        if($result['cash_refund'])
            Wallet::updatewallet($order->user_id, 'Refund for order cancellation from order id: '.$order->refid, 'Credit',$result['cash_refund'], 'CASH', $order->id);

        event(new OrderCancelled($order));
        event(new LogOrder($order));

    }

    private function calculateTotalAfterCancellation($order){

        $delivery_charge=0;
        $savings=0;
        $total_cost=0;
        $coupon_discount=0;
        $eligible_goldcash=0;
        $balance_used=0;

        //calculate total cost after cancellation
        foreach($order->details as $item) {
            $total_cost=$total_cost+($item->total_quantity-$item->cancel_returned)*($item->product->price??0);
            $savings=$savings+($item->total_quantity-$item->cancel_returned)*(($item->product->price??0)-($item->product->cut_price));

            if($item->type=='subscription'){
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
                if(!isset($daywise_delivery_total))
                    $daywise_delivery_total[$item->start_date]=0;
                $daywise_delivery_total[$item->start_date]=$daywise_delivery_total[$item->start_date]+$item->product->price*$item->quantity;
            }
        }

        if(!empty($daywise_delivery_total)){
            foreach($daywise_delivery_total as $key=>$val){
                if($order->customer->membership_expiry < $key && $val< config('myconfig.delivery_charges_min_order')['non_member']){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }else if($order->customer->membership_expiry >= $key && $val< config('myconfig.delivery_charges_min_order')['member']){
                    $delivery_charge=$delivery_charge+($delivery->param_value??0);
                }
            }
        }

        //check if counpon applied
        //find discount by applying coupon
        if($order->coupon){
            $coupon_discount=$this->calcCouponDiscount($order);
        }

        //calculate eligible goldcash
        if($order->use_points){
            $eligible_goldcash=$this->calcGoldCash($order);
        }


        if($order->use_balance)
        {
            if($order->balance_used > $total_cost+$delivery_charge-$coupon_discount-$eligible_goldcash){
                $balance_used=$total_cost+$delivery_charge-$coupon_discount-$eligible_goldcash;
            }else{
                $balance_used=$order->balance_used;
            }
        }else{
            $balance_used=0;
        }

        $cash_refund=(($order->total_cost+$delivery_charge-$order->coupon_discount-$order->points_used)-($total_cost+$delivery_charge-$coupon_discount-$eligible_goldcash) >0)?(($order->total_cost+$delivery_charge-$order->coupon_discount-$order->points_used)-($total_cost+$delivery_charge-$coupon_discount-$eligible_goldcash)):0;

        $points_refund=($order->points_used-$eligible_goldcash)>0?($order->points_used-$eligible_goldcash):0;

        return compact('total_cost','savings','delivery_charge','balance_used','cash_refund','points_refund','eligible_goldcash');


    }


    private function cancelCompleteOrder($order){
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


    private function calcCouponDiscount($order){
        $coupon_discount=0;
        $coupon=Coupon::active()
            ->with(['categories'=>function($categories){
                $categories->select('sub_category.id');
            }])
            ->with(['specialcategories'=>function($specialcategories){
                $specialcategories->select('special_category.id');
            }])
            ->where('code', $order->coupon)->first();
        if($coupon){
            $coupon_discount=$order->getCouponDiscount($coupon);
        }
        return $coupon_discount;
    }

    private function calcGoldCash($order){

        $eligible_goldcash=0;
        foreach($order->details as $d){
            $eligible_goldcash=$eligible_goldcash+($d->price*$d->product->eligible_goldcash/100)*($d->total_quantity-$d->cancel_returned);
        }
        return $eligible_goldcash;

    }

}
