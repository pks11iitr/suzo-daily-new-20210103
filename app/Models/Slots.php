<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel as Model;

class Slots extends Model
{
    use HasFactory,Active;
    protected $table='time_slot';

    protected $fillable=['name', 'from_time','to_time','isactive','slot_capacity'];

    protected $hidden = ['created_at','deleted_at','updated_at'];



//    public static function getNextDeliverySlot($city=null){
//
//        $date=date('Y-m-d');
//        $time=date('H:i:s');
//        $text='Today';
//        while(true){
//            $timeslot=TimeSlot::where('from_time', '>=', $time)
//                ->orderBy('from_time', 'asc')
//                ->get();
//
//            //echo "Checking For $date, $time";
//
//            $orders=Order::where('status', 'confirmed')
//                ->groupBy('delivery_slot', DB::raw('DATE(delivery_date)'))
//                ->where(DB::raw('DATE(updated_at)'), $date)
//                ->select(DB::raw('count(delivery_slot) as available'), 'delivery_slot')
//                ->get();
//
//            $availability=[];
//            foreach($orders as $o){
//                $availability[$o->delivery_slot]=$o->available;
//            }
//            //print_r($availability);
//            foreach($timeslot as $ts){
//                if($ts->slot_capacity > ($availability[$ts->id]??0)){
//
//                       return ['slot_id'=>$ts->id,  'next_slot'=>$text.' '.$ts->name, 'date'=>$date];
//
//                }
//            }
//
//            $date=date('Y-m-d', strtotime('+1 days', strtotime($date)));
//            $time='05:00:00';
//            //sleep(1);
//            if($text=='Today')
//                $text='Tomorrow';
//            else if($text=='Tomorrow')
//                $text=date('d/m/Y', strtotime($date));
//            else
//                $text=date('d/m/Y', strtotime($date));
//        }
//
//    }

    public static function getNextDeliverySlot($city=null){

        $date=date('Y-m-d');
        $time=date('H:i:s');
        $text='Today';
        while(true){
            $timeslot=TimeSlot::where('from_time', '>=', $time)
                ->orderBy('from_time', 'asc')
                ->get();

            //echo "Checking For $date, $time";

            $orders=Order::where('status', 'confirmed')
                ->groupBy('delivery_slot', 'delivery_date')
                ->where('delivery_date', $date)
                ->select(DB::raw('count(delivery_slot) as available'), 'delivery_slot')
                ->where('status', 'confirmed')
                ->get();

            $availability=[];
            foreach($orders as $o){
                $availability[$o->delivery_slot]=$o->available;
            }
            //print_r($availability);
            foreach($timeslot as $ts){
                //if( $date > date('Y-m-d') || $ts->from_time > $time){
                if($ts->slot_capacity > ($availability[$ts->id]??0)){

                    return ['slot_id'=>$ts->id,  'next_slot'=>$text.' '.$ts->name, 'date'=>$date];

                }
                //}
            }

            $date=date('Y-m-d', strtotime('+1 days', strtotime($date)));
            $time='05:00:00';
            //sleep(1);
            if($text=='Today')
                $text='Tomorrow';
            else if($text=='Tomorrow')
                $text=date('d/m/Y', strtotime($date));
            else
                $text=date('d/m/Y', strtotime($date));
        }

    }

    public static function getAvailableTimeSlotsList($city=null){

        $date=date('Y-m-d');
        $time=date('H:i:s');
        $text='Today';

        $time_slots=[];

        $slot_timer=0;
        while(count($time_slots)<50){
            if(empty($city)){
                $timeslot=TimeSlot::where('from_time', '>=', $time)
                    ->orderBy('from_time', 'asc')
                    ->get();
            }else{
                $timeslot=TimeSlot::where('from_time', '>=', $time)
                    ->orderBy('from_time', 'asc')
                    ->where('city', $city)
                    ->get();
            }


            //echo "Checking For $date, $time";

            $orders=Order::where('status', 'confirmed')
                ->groupBy('delivery_slot', 'delivery_date')
                ->where('delivery_date', $date)
                ->select(DB::raw('count(delivery_slot) as available'), 'delivery_slot')
                ->get();

            $availability=[];
            foreach($orders as $o){
                $availability[$o->delivery_slot]=$o->available;
            }
            //print_r($availability);
            foreach($timeslot as $ts){
                if($ts->slot_capacity > ($availability[$ts->id]??0)){

                    //return ['slot_id'=>$ts->id,  'next_slot'=>$text.' '.$ts->from_time.' - '.$ts->to_time, 'date'=>$date];
                    $time_slots[]=[
                        'slot_id'=>$ts->id.'**'.$date,
                        'name'=>$text.' '.$ts->name
                    ];


                }
            }

            $date=date('Y-m-d', strtotime('+1 days', strtotime($date)));
            $time='05:00:00';
            //sleep(1);
            if($text=='Today')
                $text='Tomorrow';
            else if($text=='Tomorrow')
                $text=date('d/m/Y', strtotime($date));
            else
                $text=date('d/m/Y', strtotime($date));

        }

        return $time_slots;

    }


}
