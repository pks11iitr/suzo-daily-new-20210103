<?php

namespace App\Console\Commands;

use App\Models\DailyDelivery;
use App\Models\Order;
use App\Models\TimeSlot;
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
                  $details->whereIn('time_slot_id', $ts)
                      ->where('scheduled_quantity', '<', DB::raw('total_quantity'))
                      ->where('order_details.status', 'pending');
                }])
                ->whereNotIn('orders.status', ['pending'])
                ->get();

        if(count($orders)){

            foreach($orders as $order){
                foreach( $order->details as $d){
                    $delivery=DailyDelivery::create([
                        'user_id'=>$order->user_id,
                        'order_id'=>$order->id,
                        'detail_id'=>$d->id,
                        'product_id'=>$d->product_id,
                        'quantity'=>$d->quantity,
                        'delivery_date'=>$delivery_date,
                        'delivery_time_slot'=>$d->time_slot_id,
                        'address_id'=>$order->address_id,
                        'rider_id'=>null,
                    ]);
                    if($delivery){
                        if($d->type=='once'){
                            $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$d->quantity), 'status'=>'completed']);
                        }else{
                            if($d->total_quantity==$d->scheduled_quantity+$d->quantity){
                                $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$d->quantity), 'status'=>'completed']);
                            }else{
                                $d->update(['scheduled_quantity'=>DB::raw('scheduled_quantity+'.$d->quantity)]);
                            }
                        }
                    }
                }
            }

        }



    }
}
