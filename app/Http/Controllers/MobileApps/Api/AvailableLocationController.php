<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\WorkLocations;
use Illuminate\Http\Request;

class AvailableLocationController extends Controller
{
    public function getAreaList(Request $request){

        $city=$request->city;

        $chosen_city=null;
        foreach(config('myconfig.cities') as $key=>$value){
            if(strtolower($value)==strtolower($city))
                $chosen_city=$key;
        }

        $areas=[];
        if($chosen_city)
            $areas=Area::where('city_id', $chosen_city)
                ->select('name', 'id')
                ->get();

        if(count($areas)){
            return [
                'status'=>'success',
                'data'=>compact('areas')
            ];
        }

        return [
            'status'=>'failed',
            'message'=>'We dont deliver in this area.'
        ];


    }

    public function checkServiceAvailability(Request $request)
    {
        $location = $request->location;
        $city=$request->city;
        $city_id=null;
        foreach(config('myconfig.cities') as $key=>$value){
            if(strtolower($value)==strtolower($city))
                $city_id=$key;
        }

        if (!$city_id)
            return [
                'status' => 'failed',
                'message' => 'Location is not serviceable'
            ];

        if (!empty($location)) {

            $json = json_decode($location, true);
            if (count($json) >= 4) {
                $json = array_reverse($json);
                $locality1 = $json[3]['long_name'] ?? '';
                $locality2 = $json[4]['long_name'] ?? '';
                $locality3 = $json[5]['long_name'] ?? '';
                $locality4 = $json[6]['long_name'] ?? '';
                $locality5 = $json[7]['long_name'] ?? '';

                $location = Area::active()
                    ->where(function ($query) use ($locality1, $locality2, $locality3, $locality4,$locality5)
                    {
                        $query->where('name', $locality1)
                            ->orWhere('name', $locality2)
                            ->orWhere('name', $locality3)
                            ->orWhere('name', $locality4)
                            ->orWhere('name', $locality5);
                    })
                    ->where('city_id', $city_id)
                    ->first();
                if ($location) {
                    return [
                        'status' => 'success',
                        'message' => 'Location is serviceable',
                        'location_id' => $location->id
                    ];
                }
            }
        }

        return [
            'status'=>'failed',
            'message'=>'Location is not serviceble'
        ];

    }
}
