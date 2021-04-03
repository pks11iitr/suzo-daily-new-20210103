<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Cart;
use App\Models\Category;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index(Request $request){
        $user = auth()->guard('customerapi')->user();

        $bannersobj= Banner::active()->whereIn('type',[2,3])->get();
        $banners=[];
        $secondbanner=[];
        foreach($bannersobj as $b){

            $banner=[
                'image'=>$b->image,
                'category_id'=>($b->entity_type=='App\Models\SpecialCategory')?'':(!empty($b->parent_id)?$b->parent_id:$b->entity->id),
                'subcategory_id'=>($b->entity_type=='App\Models\SpecialCategory')?'':(!empty($b->parent_id)?$b->entity_id:''),
                'special_category'=>($b->entity_type=='App\Models\SpecialCategory')?$b->entity_id:''
            ];

            if($b->type==2){
                $banners[]=$banner;
            }else{
                $secondbanner[]=$banner;
            }
        }
        $categories=Category::active()->get();

        $user=[
            'name'=>$user->name??'',
            'image'=>$user->image??'',
            'mobile'=>$user->mobile??'',
            'membership_name'=>isset($user)?($user->membership_expiry>=date('Y-m-d')?$user->membership->name:''):''
        ];
        $home_sections=HomeSection::active()
            ->with('entities.entity')
            ->orderBy('sequence_no', 'asc')
            ->get();

        foreach($home_sections as $section){
            $new_sec=[];
            switch($section->type){
                case 'banner':
                    $new_sec['type']='banner';
                    $new_sec['title']='';
                    $new_sec['name']='';
                    $new_sec['bannerdata']=[
                        'image'=>$section->entities[0]->entity->image??'',
                        'category_id'=>($section->entities[0]->entity_type=='App\Models\SpecialCategory')?'':(!empty($section->entities[0]->entity->parent_id)?$section->entities[0]->entity->parent_id:$section->entities[0]->entity->entity_id),
                        'subcategory_id'=>($section->entities[0]->entity_type=='App\Models\SpecialCategory')?'':(!empty($section->entities[0]->entity->parent_id)?$section->entities[0]->entity->entity_id:''),
                        'special_category'=>($section->entities[0]->entity_type=='App\Models\SpecialCategory')?$section->entities[0]->entity_id:'',
                    ];
                    $new_sec['products']=[];
                    $new_sec['subcategory']=[];
                    break;
                case 'product1':
                    $new_sec['type']='product1';
                    $new_sec['name']=$section->name;
                    $new_sec['bannerdata']=[
                        'image'=>'',
                        'category_id'=>'',
                        'subcategory_id'=>'',
                    ];
                    $new_sec['subcategory']=[];
                    $new_sec['products']=[];
                    foreach($section->entities as $entity){
                        $entity1=$entity->entity;
                        $entity1->cart_quantity=$request->cart[$entity1->id]['cart_quantity']??0;
                        $entity1->cart_type=$request->cart[$entity1->id]['cart_type']??'once';
                        $new_sec['products'][]=$entity1;
                    }
                    break;
                case 'product2':
                    $new_sec['type']='product2';
                    $new_sec['name']=$section->name;
                    $new_sec['bannerdata']=[
                        'image'=>'',
                        'category_id'=>'',
                        'subcategory_id'=>'',
                    ];
                    $new_sec['subcategory']=[];
                    $new_sec['products']=[];
                    foreach($section->entities as $entity){
                        $entity1=$entity->entity;
                        $entity1->cart_quantity=$request->cart[$entity1->id]['cart_quantity']??0;
                        $entity1->cart_type=$request->cart[$entity1->id]['cart_type']??'once';
                        $new_sec['products'][]=$entity1;
                    }
                    break;
                case 'subcategory':
                    $new_sec['type']='subcategory';
                    $new_sec['name']=$section->name;
                    $new_sec['products']=[];
                    $new_sec['bannerdata']=[
                        'image'=>'',
                        'category_id'=>'',
                        'subcategory_id'=>'',
                    ];
                    $new_sec['subcategory']=[];
                    foreach($section->entities as $entity){
                        $new_sec['subcategory'][]=[
                            'categoryname'=>$entity->name,
                            'categorytitle'=>$entity->title,
                            'categoryimage'=>$entity->image,
                            'subcategory_id'=>$entity->entity_id,
                            'category_id'=>$entity->parent_category,
                        ];
                    }
                    break;
            }

            $sections[]=$new_sec;

        }

        $cart_count=$request->item_type_count??0;

       //if($banners->count()>0 || $categories->count()>0){
           return [
               'status'=>'success',
               'message'=>'success',
               'data'=>compact('banners','categories','secondbanner','user','sections', 'cart_count')
           ];
//       }else{
//           return [
//               'status'=>'failed',
//               'message'=>'No Record Found'
//           ];
//       }

    }

    //login page banner api
    public function login_Banner(Request $request){

        $banners= Banner::active()->where('type',1)->get();
        return [
            'status'=>'success',
            'message'=>'success',
            'data'=>compact('banners')
        ];
    }
}
