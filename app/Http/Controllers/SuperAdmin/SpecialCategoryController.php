<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SpecialCategory;
use Illuminate\Http\Request;

class SpecialCategoryController extends Controller
{
    public function index(Request $request){

//        $category=Category::where(function($category) use($request){
//            $category->where('name','LIKE','%'.$request->search.'%');
//        });

//        if($request->ordertype)
//            $category=$category->orderBy('name', $request->ordertype);

        $category=SpecialCategory::get();
        return view('admin.specialcategory.view',['category'=>$category]);
    }

    public function create(Request $request){
        return view('admin.specialcategory.add');
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            //'image'=>'required|image'
        ]);

        if($category=SpecialCategory::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive]))
        {
            //$category->saveImage($request->image, 'category');
            return redirect()->route('specialcategory.list')->with('success', 'category has been created');
        }
        return redirect()->back()->with('error', 'category create failed');
    }

    public function edit(Request $request,$id){
        $category = SpecialCategory::findOrFail($id);
        return view('admin.specialcategory.edit',['category'=>$category]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            //'image'=>'image'
        ]);

        $category = SpecialCategory::findOrFail($id);

        $category->update([
            'isactive'=>$request->isactive,
            'name'=>$request->name,
        ]);
//        if($request->image){
//            $category->saveImage($request->image, 'category');
//        }

        if($category)
        {
            return redirect()->route('specialcategory.list')->with('success', 'Category has been updated');
        }
        return redirect()->back()->with('error', 'Category update failed');

    }
}
