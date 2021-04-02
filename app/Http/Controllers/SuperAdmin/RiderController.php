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
            'email'=>'required|unique:riders',
            'mobile'=>'required|unique:riders',
            'address'=>'required',
            'state'=>'required',
            'city'=>'required',
            'password'=>'required',
            'image'=>'required|image',
            'isactive'=>'required|integer'
        ]);

        if(Rider::where('mobile', $request->mobile)->first()){
            return redirect()->back()->with('error', 'Mobile Number Already Exists Registers');
        }

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
            return redirect()->route('rider.list')->with('success', 'Rider has been created');
        }
        return redirect()->back()->with('error', 'Rider create failed');
    }

    public function edit(Request $request,$id){
        $rider = Rider::findOrFail($id);
        return view('admin.rider.edit',['rider'=>$rider]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:riders,email,'.$id,
            'mobile'=>'required|unique:riders,mobile,'.$id,
            'address'=>'required',
            'state'=>'required',
            'city'=>'required',
            'image'=>'image',
            'isactive'=>'required|integer'
        ]);

        $rider = Rider::findOrFail($id);
        if($request->mobile!=$rider->mobile){
            if($rider1=Rider::where('mobile', $request->mobile)->first()){
                return redirect()->back()->with('error', 'Mobile Number Already Exists Registers');
            }
        }
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
            return redirect()->route('rider.list')->with('success', 'Rider has been updated');
        }
        return redirect()->back()->with('error', 'Rider create failed');
    }

}
