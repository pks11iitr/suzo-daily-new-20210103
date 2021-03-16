<?php

namespace App\Http\Controllers\MobileApps\Api;

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

        return [
            'status'=>'success',
            'products'=>$products,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total
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



        return [
            'status'=>'success',
            'products'=>$products,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
        ];
    }

    public function sectionproducts(Request $request, $section_id){

        $entities=HomeSectionEntity::with('entity')
        ->where('home_section_id', $section_id);

        $entities=$entities->paginate(20);

        $products=[];
        foreach($entities as $entity){
            $product=$entity->entity;
            $product->cart_value=$request->cart[$product->id]['cart_quantity']??0;
            $product->cart_type=$request->cart[$product->id]['cart_type']??'once';
            $products[]=$product;
        }

        return [
            'status'=>'success',
            'products'=>$products,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
        ];
    }

    public function search_products(Request $request){

        $products=Product::active();
        if(!empty($request->search))
            $products = $products->where('name', 'like', "%".$request->search."%");

        $searchproducts=$products->paginate(20);
        foreach($searchproducts as $product){
            $product->cart_value=$request->cart[$product->id]['cart_quantity']??0;
            $product->cart_type=$request->cart[$product->id]['cart_type']??'once';
        }

        return [
            'status'=>'success',
            'data'=>$searchproducts,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,
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

            return $item->toArray();

        }


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
                 'type'=>$request->cart[$product->id]['cart_type']??'once',
                 'start_date'=>$item->start_date??$next_slot['date'],
                 'time_slot'=>$item->time_slot??$next_slot['id'],
                 'no_of_days'=>$item->no_of_days??0,
                 'days'=>$item->days??[],
                 'timeslot'=>$timeslot,
                'start_date_text'=>date('d M', strtotime($item->start_date??$next_slot['date'])),
                'time_slot_text'=>isset($item->time_slot)?($item->time_slot->name):($next_slot['name']??'NA')
    );

        return [
            'status'=>'success',
            'data'=>$productdetails,
            'cart_total'=>$request->cart_count,
            'cart_total_price'=>$request->cart_total,

        ];
    }



}
