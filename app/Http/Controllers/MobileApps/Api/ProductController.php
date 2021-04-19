<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Models\Banner;
use App\Models\BookDay;
use App\Models\Cart;
use App\Models\HomeSectionEntity;
use App\Models\Product;
use App\Models\Slots;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function products(Request $request,$cat_id,$subcat_id=null){

        if($subcat_id){
              $product=Product::active()->whereHas('subcategory', function($category) use($subcat_id){
                  $category->where('sub_category.id', $subcat_id);
                });
        }else{
              $product=Product::active()->whereHas('category', function($category) use($cat_id){
                  $category->where('categories.id', $cat_id);
                });
        }

        $products=$product->paginate(20);

        foreach($products as $product){
            $product->cart_value=$request->cart[$product->id]['cart_quantity']??0;
            $product->cart_type=$request->cart[$product->id]['cart_type']??'once';
        }

        $cart_count=$request->item_type_count??0;

        return [
            'status'=>'success',
            'products'=>$products,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
            'cart_count'=>$cart_count
        ];
    }

    public function specialproducts(Request $request, $special_id){

        $product=Product::active()->whereHas('specialcategory', function($category) use($special_id){
            $category->where('special_category.id', $special_id);
        });

        $productsobj=$product->paginate(20);
        $products=[];
        foreach($productsobj as $product){
            $product->cart_value=$request->cart[$product->id]['cart_quantity']??0;
            $product->cart_type=$request->cart[$product->id]['cart_type']??'once';
            $products[]=$product;
        }

        $cart_count=$request->item_type_count??0;

        $next_page_url=$productsobj->nextPageUrl();
        $prev_page_url=$productsobj->previousPageUrl();

        return [
            'status'=>'success',
            'products'=>$products,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
            'cart_count'=>$cart_count,
            'next_page_url'=>$next_page_url,
            'prev_page_url'=>$prev_page_url
        ];
    }

    public function sectionproducts(Request $request, $section_id){

        $entities=HomeSectionEntity::with('entity')
        ->where('home_section_id', $section_id);

        $entities=$entities->paginate(20);

        $next_page_url=$entities->nextPageUrl();
        $prev_page_url=$entities->previousPageUrl();

        $products=[];
        foreach($entities as $entity){
            $product=$entity->entity;
            $product->cart_value=$request->cart[$product->id]['cart_quantity']??0;
            $product->cart_type=$request->cart[$product->id]['cart_type']??'once';
            $products[]=$product;
        }

        $cart_count=$request->item_type_count??0;

        return [
            'status'=>'success',
            'products'=>$products,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
            'cart_count'=>$cart_count,
            'next_page_url'=>$next_page_url,
            'prev_page_url'=>$prev_page_url
        ];
    }


    public function product_detail(Request $request,$id){

        $user=$request->user;
        //var_dump($user);die;
        $product=Product::active()->findOrFail($id);
        //slots list

        $timeslot=Slots::active()->select('id','name')->get();

        $next_slot=TimeSlot::getNextDeliverySlot();
        //die;
        if($user){
            //die;
            $item=Cart::with(['days', 'timeslot'])
                ->where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->first();

            if($item){
                Cart::updateSingleItemTimeSlot($item, $next_slot);
                $item=Cart::with(['days', 'timeslot'])
                    ->where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->first();
            }

            //$item->days()->sync([3,4,5]);

            //return $item->toArray();

        }

        $cart_count=$request->item_type_count??0;

        $productdetails=array(
                 'id'=>$product->id,
                 'name'=>$product->name,
                 'image'=>$product->image,
                 'description'=>$product->description??'',
                 'price'=>$product->price,
                 'cut_price'=>$product->cut_price,
                 'discount'=>$product->discount,
                 'unit'=>$product->unit,
                 'can_be_subscribed'=>$product->can_be_subscribed,
                 'min_qty'=>$product->min_qty,
                 'max_qty'=>$product->max_qty,
                 'stock'=>$product->stock,
                 'quantity'=>$request->cart[$product->id]['cart_quantity']??0,
                 'type'=>$request->cart[$product->id]['cart_type']??($product->can_be_subscribed?'subscription':'once'),
                 'start_date'=>$item->start_date??$next_slot['date'],
                 'time_slot'=>$item->time_slot_id??$next_slot['id'],
                 'no_of_days'=>$item->no_of_days??($product->can_be_subscribed?15:1),
                 'days'=>$item->days??BookDay::get(),
                 'timeslot'=>$timeslot,
                'start_date_text'=>date('d M', strtotime($item->start_date??$next_slot['date'])),
                'time_slot_text'=>isset($item->time_slot_id)?($item->timeslot->name):($next_slot['time']??'NA')
    );

        return [
            'status'=>'success',
            'data'=>$productdetails,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
            'cart_count'=>$cart_count

        ];
    }

    public function offers(Request $request){

        $bannersobj=Banner::active()
            ->where('entity_type', 'App\Models\SpecialCategory')
            ->get();

        foreach($bannersobj as $b){

            $banner=[
                'image'=>$b->image,
                'category_id'=>($b->entity_type=='App\Models\SpecialCategory')?'':(!empty($b->parent_id)?$b->parent_id:$b->entity->id),
                'subcategory_id'=>($b->entity_type=='App\Models\SpecialCategory')?'':(!empty($b->parent_id)?$b->entity_id:''),
                'special_category'=>($b->entity_type=='App\Models\SpecialCategory')?$b->entity_id:''
            ];

            $banners[]=$banner;

        }

        $products=Product::active()
            ->whereHas('specialcategory', function($specialcategory){
                $specialcategory->where('isactive', 1);
            });

        $productsobj=$products->paginate(20);

        $products=[];
        foreach($productsobj as $product){
            $product->cart_value=$request->cart[$product->id]['cart_quantity']??0;
            $product->cart_type=$request->cart[$product->id]['cart_type']??'once';
            $products[]=$product;
        }

        $cart_count=$request->item_type_count??0;
        $next_page_url=$productsobj->nextPageUrl();
        $prev_page_url=$productsobj->previousPageUrl();

        return [
            'status'=>'success',
            'products'=>$products,
            'banners'=>$banners,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
            'cart_count'=>$cart_count,
            'next_page_url'=>$next_page_url,
            'prev_page_url'=>$prev_page_url
        ];

    }


}
