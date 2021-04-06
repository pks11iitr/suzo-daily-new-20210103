<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function index(Request $request){
        $stores =User::where('id', '>', 1)->paginate(10);
        return view('admin.store.view',['stores'=>$stores]);
    }

    public function create(Request $request){
        return view('admin.store.add');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:users',
            'mobile'=>'required|unique:users',
            'address'=>'required',
            'password'=>'required',
            'status'=>'required'
        ]);

        if($area=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'address'=>$request->address,
            'password'=> Hash::make($request->password),
            'status'=>$request->status,
        ]))

        {
            //var_dump($area->toArray());die;
            $area->assignRole('store');
            return redirect()->route('stores.list')->with('success', 'store has been created');
        }
        return redirect()->back()->with('error', 'store create failed');
    }

    public function edit(Request $request,$id){
        $store =User::findOrFail($id);
        return view('admin.store.edit',['store'=>$store]);
    }

    public function update(Request $request ,$id){
        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:users,email,'.$id,
            'mobile'=>'required|unique:users,mobile,'.$id,
            'address'=>'required',
            //'password'=>'required',
            'status'=>'required'
        ]);

        $store =User::findOrFail($id);

        if($request->password) {
            $store->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'status' => $request->status,
            ]);
        }else{
            $store->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'status' => $request->status,
            ]);
        }
        if($store)
        {
            if(!$store->hasRole('store')){
                $store->assignRole('store');
            }
            return redirect()->route('stores.list')->with('success', 'store has been updated');
        }
        return redirect()->back()->with('error', 'store update failed');
    }
}
