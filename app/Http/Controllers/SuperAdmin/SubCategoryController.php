<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Storage;

class SubCategoryController extends Controller
{
    public function index(Request $request){

//        $subcategory=SubCategory::where(function($subcategory) use($request){
//            $subcategory->where('name','LIKE','%'.$request->search.'%');
//        });
//
//        if($request->ordertype)
//            $subcategory=$subcategory->orderBy('name', $request->ordertype);

        $subcategory=SubCategory::with('category')->get();
        return view('admin.subcategory.view',['subcategory'=>$subcategory]);
    }

    public function create(Request $request){
        $categories=Category::active()->get();
        return view('admin.subcategory.add',['categories'=>$categories]);
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'category_id'=>'required',
        ]);

        if($subcategory=SubCategory::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'category_id'=>$request->category_id
        ]))
        {

            return redirect()->route('subcategory.list')->with('success', 'subcategory has been created');
        }
        return redirect()->back()->with('error', 'subcategory create failed');
    }

    public function edit(Request $request,$id){
        $subcategory = SubCategory::findOrFail($id);
        $categories=Category::active()->get();
        return view('admin.subcategory.edit',['subcategory'=>$subcategory,'categories'=>$categories]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'category_id'=>'required'
        ]);

        $subcategory = SubCategory::findOrFail($id);

        $subcategory->update([
            'isactive'=>$request->isactive,
            'category_id'=>$request->category_id,
            'name'=>$request->name,
        ]);

        if($subcategory)
        {
            return redirect()->route('subcategory.list')->with('success', 'subcategory has been updated');
        }
        return redirect()->back()->with('error', 'subcategory update failed');

    }

//    public function delete(Request $request, $id){
//        Document::where('id', $id)->delete();
//        return redirect()->back()->with('success', 'Document has been deleted');
//    }
//
}
