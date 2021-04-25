<?php

namespace App\Console\Commands;

use App\Events\OrderCancelled;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Wallet;
use Illuminate\Console\Command;
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
                if($d->status!='cancelled' || ($d->status=='cancelled' && $d->cancel_raised==0)){
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

    public function cancelCompleteOrder($order){
        if($order->points_used>0)
            Wallet::updatewallet($order->user_id, 'Refund for order cancellation from order id: '.$order->refid, 'Credit',$order->points_used, 'POINT', $order->id);

        $cash_return=$order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->points_used;
        if($cash_return>0)
            Wallet::updatewallet($order->user_id, 'Refund for order cancellation from order id: '.$order->refid, 'Credit',$cash_return, 'CASH', $order->id);

        $order->details()->update(['cancel_raised'=>false]);

        event(new OrderCancelled($order));
    }
}
