<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OfferDetail;
use Illuminate\Http\Request;

class OfferDetailController extends Controller
{
    public function index(Request $request){

        $offers=OfferDetail::get();
        return view('admin.offer.view',compact('offers'));
    }

    public function create(Request $request){
        return view('admin.offer.add');
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'image'=>'required|image',
            'description'=>'required'
        ]);

        if($offer=OfferDetail::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'description'=>$request->isactive,
            'image'=>'a']))
        {
            $offer->saveImage($request->image, 'offers');
            return redirect()->route('offer.list')->with('success', 'Offer has been created');
        }
        return redirect()->back()->with('error', 'category create failed');
    }

    public function edit(Request $request,$id){
        $offer = OfferDetail::findOrFail($id);
        return view('admin.offer.edit',compact('offer'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            //'image'=>'image',
            'description'=>'required',
        ]);

        $offer = OfferDetail::findOrFail($id);

        $offer->update([
            'isactive'=>$request->isactive,
            'name'=>$request->name,
            'description'=>$request->description,
        ]);
        if($request->image){
            $offer->saveImage($request->image, 'category');
        }

        if($offer)
        {
            return redirect()->route('offer.list')->with('success', 'Offer has been updated');
        }
        return redirect()->back()->with('error', 'Offer update failed');

    }
}
