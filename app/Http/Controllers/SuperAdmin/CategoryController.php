<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Storage;

class CategoryController extends Controller
{
    public function index(Request $request){

//        $category=Category::where(function($category) use($request){
//            $category->where('name','LIKE','%'.$request->search.'%');
//        });

//        if($request->ordertype)
//            $category=$category->orderBy('name', $request->ordertype);

        $category=Category::get();
        return view('admin.category.view',['category'=>$category]);
    }

    public function create(Request $request){
        return view('admin.category.add');
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'image'=>'required|image'
        ]);

        if($category=Category::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'image'=>'a']))
        {
            $category->saveImage($request->image, 'category');
            return redirect()->route('category.list')->with('success', 'category has been created');
        }
        return redirect()->back()->with('error', 'category create failed');
    }

    public function edit(Request $request,$id){
        $category = Category::findOrFail($id);
        return view('admin.category.edit',['category'=>$category]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'image'=>'image'
        ]);

        $category = Category::findOrFail($id);

            $category->update([
                'isactive'=>$request->isactive,
                'name'=>$request->name,
                ]);
        if($request->image){
            $category->saveImage($request->image, 'category');
        }

        if($category)
        {
            return redirect()->route('category.list')->with('success', 'Category has been updated');
        }
        return redirect()->back()->with('error', 'Category update failed');

    }

//    public function delete(Request $request, $id){
//        Document::where('id', $id)->delete();
//        return redirect()->back()->with('success', 'Document has been deleted');
//    }
//
}
