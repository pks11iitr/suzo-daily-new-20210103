<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function aboutUs(Request $request){
        return view('about-us');
    }


    public function tnc(Request $request){
        return view('t-n-c');
    }


}
