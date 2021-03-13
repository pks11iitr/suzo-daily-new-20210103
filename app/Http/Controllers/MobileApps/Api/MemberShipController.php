<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MemberShipController extends Controller
{
    public function index(Request $request){

        $membershipobj=Membership::active()->get();
        $memberships=[];

        foreach($membershipobj as $membership){
            $memberships[]=[
                'id'=>$membership->id,
                'price'=>$membership->price,
                'cut_price'=>$membership->cut_price,
                'title'=>$membership->title,
                'description'=>explode('***', $membership->description),
                'validity'=>$membership->months,
            ];
        }

        $active_membership=1;

        return [
            'status'=>'success',
            'data'=>compact('memberships', 'active_membership')
        ];

    }
}
