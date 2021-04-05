<?php

namespace App\Console\Commands;

use App\Models\DailyDelivery;
use App\Models\TimeSlot;
use App\Services\Notification\FCMNotification;
use Illuminate\Console\Command;

class NotifyUserDeliveries extends Command
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
        $time=date('H:i');
        $date=date('Y-m-d');
        if($time > '15:00'){
            $ts=TimeSlot::where('from_time', '18:00:00')->get();
        }else{
            $ts=TimeSlot::where('from_time', '06:00:00')->get();
        }

        while($delivery=DailyDelivery::where('notification_status', 1)->where('status', 'pending')->where('delivery_time_slot', $ts->id)->where('delivery_date', $date)->first()){

            $title='Grocery Delivered';
            $message='Your groceries delivery scheduled on '.$date.'('.$ts->name.')'.' has been completed';

            $delivery->customer->notify(new FCMNotification($title,$message, ['type'=>'order', 'title'=>$title, 'body'=>$message, 'order_id'=>$delivery->order_id], 'order_details'));

            DailyDelivery::where('detail_id', $delivery->detail_id)
                ->where('delivery_date', $delivery->delivery_date)
                ->where('delivery_time_slot', $delivery->delivery_time_slot)
                ->where('notification_status', 1)
                ->update(['notification_status'=> 2]);

        }

    }
}
