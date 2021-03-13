<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        $categories=Category::active()->inRandomOrder()->get();

        if($categories->count()>0){
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>compact('categories')
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'No Record Found'
            ];
        }

    }

    public  function subcategory(Request $request,$id){
        $subcategoriesobj=SubCategory::active()->where('category_id',$id)->get();
        $subcategories=[];
        $subcategories[]=['id'=>'', 'name'=>'All'];
        foreach($subcategoriesobj as $sub){
            $subcategories[]=['id'=>$sub->id, 'name'=>$sub->name];
        }
        return [
            'status'=>'success',
            'message'=>'success',
            'data'=>compact('subcategories')
        ];

    }
}
