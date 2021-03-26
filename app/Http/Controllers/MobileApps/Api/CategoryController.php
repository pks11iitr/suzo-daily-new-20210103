<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        $categories=Category::active()->with(['subcategories'=>function($subcat){
            $subcat->where('isactive', 1);
        }])->get();

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

        $bannersobj= Banner::active()->whereIn('type',[2,3])->get();

        $banners=[];

        foreach($bannersobj as $b){

            $banner=[
                'image'=>$b->image,
                'category_id'=>($b->entity_type=='App\Models\SpecialCategory')?'':(!empty($b->parent_id)?$b->parent_id:$b->entity->id),
                'subcategory_id'=>($b->entity_type=='App\Models\SpecialCategory')?'':(!empty($b->parent_id)?$b->entity_id:''),
                'special_category'=>($b->entity_type=='App\Models\SpecialCategory')?$b->entity_id:''
            ];

            $banners[]=$banner;

        }


        return [
            'status'=>'success',
            'message'=>'success',
            'data'=>compact('subcategories', 'banners')
        ];

    }
}
