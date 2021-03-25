<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\AreaExport;
use App\Imports\AreaImport;
use App\Models\Area;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class AreaController extends Controller
{
    public function index(Request $request){
        $arealists =Area::with(['rider', 'store'])->get();
        return view('admin.area.view',['arealists'=>$arealists]);
    }

    public function create(Request $request){
        $stores=User::where('id', '>', 1)->get();
        $riders=Area::get();
        return view('admin.area.add', compact('stores', 'riders'));
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required'
        ]);

        if($area=Area::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'store_id'=>$request->store_id,
            'rider_id'=>$request->rider_id,
            'city_id'=>$request->city_id
        ]))

        {
            return redirect()->route('area.list')->with('success', 'Area has been created');
        }
        return redirect()->back()->with('error', 'Area create failed');
    }

    public function edit(Request $request,$id){
        $arealist=Area::findOrFail($id);
        $stores=User::where('id', '>', 1)->get();
        $riders=Area::get();
        return view('admin.area.edit',['arealist'=>$arealist, 'stores'=>$stores, 'riders'=>$riders]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required'
        ]);
        $arealist=Area::findOrFail($id);
        if($arealist->update([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'store_id'=>$request->store_id,
            'rider_id'=>$request->rider_id,
            'city_id'=>$request->city_id
        ]))

        {
            //CustomerAddress::where('area', $request->name)->update(['name'=>$request->name]);
            return redirect()->route('area.list')->with('success', 'Area has been updated');
        }
        return redirect()->back()->with('error', 'Area update failed');
    }

    public function import(Request $request){

        Excel::import(new AreaImport, request()->file('select_file'));

        return redirect()->route('area.list')->with('success', 'Your Data imported successfully.');

        return redirect()->back()->with('error', 'Your Data import failed');
    }

    public function export(Request $request){

        return Excel::download(new AreaExport, 'areas.xlsx');
    }
}
