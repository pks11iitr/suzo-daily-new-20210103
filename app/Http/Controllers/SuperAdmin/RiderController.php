<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class RiderController extends Controller
{
    public function index(Request $request){
        $riders =Rider::orderBy('id', 'DESC')->paginate(10);
        return view('admin.rider.view',['riders'=>$riders]);
    }

    public function create(Request $request){
        return view('admin.rider.add');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'mobile'=>'required',
            'address'=>'required',
            'state'=>'required',
            'city'=>'required',
            'password'=>'required',
            'image'=>'required|image',
            'isactive'=>'required|integer'
        ]);

        if($rider=Rider::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'address'=>$request->address,
            'state'=>$request->state,
            'city'=>$request->city,
            'password'=> Hash::make($request->password),
            'isactive'=>$request->isactive,
            'image'=>'a']))
        {
            $rider->saveImage($request->image, 'rider');
            return redirect()->route('rider.list')->with('success', 'rider has been created');
        }
        return redirect()->back()->with('error', 'rider create failed');
    }

    public function edit(Request $request,$id){
        $rider = Rider::findOrFail($id);
        return view('admin.rider.edit',['rider'=>$rider]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'mobile'=>'required',
            'address'=>'required',
            'state'=>'required',
            'city'=>'required',
            'image'=>'image',
            'isactive'=>'required|integer'
        ]);

        $rider = Rider::findOrFail($id);

        $rider->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'state' => $request->state,
            'city' => $request->city,
            'isactive'=>$request->isactive,
            'password'=>!empty($request->password)?Hash::make($request->password):$rider->password
        ]);

        if($request->image ) {
            $rider->saveImage($request->image, 'rider');
        }

        if($rider)
        {
            return redirect()->route('rider.list')->with('success', 'rider has been updated');
        }
        return redirect()->back()->with('error', 'rider create failed');
    }

}
