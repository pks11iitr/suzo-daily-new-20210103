<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
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


    public function search_suggestions(Request $request){

        $products=Product::active();
        if(!empty($request->search))
            $products = $products->where('name', 'like', "%".$request->search."%");

        $searchproducts=$products->select('name', 'id')->get();

        return [
            'status'=>'success',
            'data'=>$searchproducts,
        ];
    }


}
