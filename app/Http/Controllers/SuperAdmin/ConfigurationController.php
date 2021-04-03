<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function index(Request $request){

        $configurations=Configuration::get();

        return view('admin.configurations.update-configurations', compact('configurations'));

    }


    public function update(Request $request){

        foreach($request->all() as $key=>$val){

            if($key=='express_delivery')
                Configuration::where('param', $key)->update(['value'=>$val, 'description'=>$request->express_description]);
            else
                Configuration::where('param', $key)->update(['value'=>$val]);

        }

        return redirect()->back()->with('success', 'All Configurations Have Been Updated');
    }
}
