<?php

namespace App\Console\Commands;

use App\Models\DailyDelivery;
use App\Models\Order;
use App\Models\Rider;
use App\Models\TimeSlot;
use App\Services\Notification\FCMNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScheduleDeliveries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:delivery';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule Daily Deliveries';

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
        $time=date('H:i');
        if($time < '21:00'){
            $ts=TimeSlot::where('from_time', '18:00:00')->get();
            $ts=$ts->map(function($element){
                return $element->id;
            });
            $delivery_date=date('Y-m-d');
        }else{
            $ts=TimeSlot::where('from_time', '06:00:00')->get();
            $ts=$ts->map(function($element){
                return $element->id;
            });
            $delivery_date=date('Y-m-d', strtotime('+1 days'));
        }
        $orders=Order::with(['details'=>function($details) use($ts){
                  $details->with('days')->whereIn('time_slot_id', $ts)
                      ->where('scheduled_quantity', '<', DB::raw('total_quantity-cancel_returned'))
                      ->where('order_details.status', 'pending');
                },'customer'=>function($customer){
            $customer->select('id','holiday_start', 'holiday_end');
        }])
                ->whereNotIn('orders.status', ['pending'])
                ->get();

        $riders=[];

        if(count($orders)){

            foreach($orders as $order){
                foreach( $order->details as $d){

                    if($d->type=='once'){

                        if(!isset($riders[$order->rider_id])){
                            $riders[$order->rider_id]=0;
                        }
                        $riders[$order->rider_id]++;

                        $quantity=$d->quantity;
                        if($quantity>0){
                            $delivery=DailyDelivery::create([
                                'user_id'=>$order->user_id,
                                'order_id'=>$order->id,
                                'detail_id'=>$d->id,
                                'product_id'=>$d->product_id,
                                'quantity'=>$quantity,
                                'delivery_date'=>$delivery_date,
                                'delivery_time_slot'=>$d->time_slot_id,
                                'address_id'=>$order->address_id,
                                'rider_id'=>$order->rider_id,
                                'store_id'=>$order->store_id,
                                'area_id'=>$order->deliveryaddress->area_id,
                            ]);
                            $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$quantity)]);
                        }

                    }else{
                        $day=date('w', strtotime($delivery_date));
                        $subcription_days=$d->days->map(function($element){
                            return $element->id;
                        })->toArray();
                        if(in_array($day, $subcription_days)){
                            if(!($delivery_date >= $order->customer->holiday_start && $delivery_date <= $order->customer->holiday_end)){
                                if(!isset($riders[$order->rider_id])){
                                    $riders[$order->rider_id]=0;
                                }
                                $riders[$order->rider_id]++;

                                if($d->total_quantity-$d->scheduled_quantity >= $d->quantity){
                                    $quantity=$d->quantity;
                                }else{
                                    $quantity=$d->total_quantity-$d->scheduled_quantity;
                                }
                                if($quantity>0){
                                    $delivery=DailyDelivery::create([
                                        'user_id'=>$order->user_id,
                                        'order_id'=>$order->id,
                                        'detail_id'=>$d->id,
                                        'product_id'=>$d->product_id,
                                        'quantity'=>$quantity,
                                        'delivery_date'=>$delivery_date,
                                        'delivery_time_slot'=>$d->time_slot_id,
                                        'address_id'=>$order->address_id,
                                        'rider_id'=>$order->rider_id,
                                        'store_id'=>$order->store_id,
                                        'area_id'=>$order->deliveryaddress->area_id,
                                    ]);

                                    $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$quantity)]);
                                }

                            }

                        }
                    }

//                    if($delivery){
//                        if($d->type=='once'){
//                            $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$d->quantity), 'status'=>'completed']);
//                        }else{
//                            if($d->total_quantity==$d->scheduled_quantity+$d->quantity){
//                                $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$d->quantity), 'status'=>'completed']);
//                            }else{
//                                $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$d->quantity)]);
//                            }
//                        }
//                    }
                }
            }

            if(!empty($riders)){
                $ridreobj=Rider::whereIn('id', array_keys($riders))->get();
                foreach($ridreobj as $r)
                    $r->notify(new FCMNotification('New Deliveries Scheduled', 'You have '.$riders[$r->id].' new deliveries.', ['type'=>'open_deliveries', 'title'=>'New Deliveries Scheduled', 'body'=>'You have '.$riders[$r->id].' new deliveries.'], 'open-deliveries'));
            }

        }



    }
}
