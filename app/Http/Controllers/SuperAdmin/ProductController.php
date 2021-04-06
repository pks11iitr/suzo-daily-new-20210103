<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\ProductsExport;
use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\CategoryProduct;
//use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use Excel;

class ProductController extends Controller
{
     public function index(Request $request){

         if($request->search){
             $products=Product::where(function($products) use($request){
                 $products->where('name','LIKE','%'.$request->search.'%');
             });
         }else{
             $products=Product::where('id','>', 0);
         }

         if($request->category_id)
             $products=$products->whereHas('category', function($category) use($request){
                 $category->where('categories.id', $request->category_id);
             });


         if($request->ordertype)
             $products=$products->orderBy('name', $request->ordertype);


         if($request->export=='yes'){
             $products=$products->with(['category', 'subcategory'])->get();
             return $this->downloadProduct($products);
         }

         $products=$products->paginate(10);
         $categories=Category::get();

         return view('admin.product.view',['products'=>$products, 'categories'=>$categories]);
              }


    public function downloadProduct($products){

        return Excel::download(new ProductsExport($products), 'products.xlsx');

    }

    public function create(Request $request){
        $categories=Category::active()->get();
        $subcategories=SubCategory::active()->get();
            return view('admin.product.add',['categories'=>$categories,'subcategories'=>$subcategories]);
               }

   public function store(Request $request){
               $request->validate([
                  			'isactive'=>'required',
                  			'name'=>'required',
                  			'description'=>'required',
                  			'company'=>'required',
                  			//'is_offer'=>'required',
                  			'stock_type'=>'required',
                  			'min_qty'=>'required',
                  			'max_qty'=>'required',
                  			'stock'=>'required',
                  			'image'=>'required|image',
                            'price'=>'required',
                            'cut_price'=>'required',
                            'cgst'=>'required',
                            'sgst'=>'required',
                            'can_be_subscribed'=>'required',
                            'subscription_cashback'=>'required',
                            'eligible_goldcash'=>'required',
                            'delivery_charge'=>'required',
                               ]);

          if($products=Product::create([
                      'name'=>$request->name,
                      'description'=>$request->description,
                      'company'=>$request->company,
                      'stock_type'=>$request->stock_type,
                      'min_qty'=>$request->min_qty,
                      'max_qty'=>$request->max_qty,
                      'stock'=>$request->stock,
                      'isactive'=>$request->isactive,
                      'price'=>$request->price,
                      'cut_price'=>$request->cut_price,
                      'cgst'=>$request->cgst,
                      'sgst'=>$request->sgst,
                      'can_be_subscribed'=>$request->can_be_subscribed,
                      'subscription_cashback'=>$request->subscription_cashback,
                      'eligible_goldcash'=>$request->eligible_goldcash,
                      'delivery_charge'=>$request->delivery_charge,
                      'image'=>'a'])){

              $added_categories=[];
              if(!empty($request->sub_cat_id)){
                  $subcat=SubCategory::with('category')
                      ->whereIn('id', $request->sub_cat_id)
                      ->get();

                  foreach($subcat as $subcategory) {
                      CategoryProduct::create([
                          'category_id' => $subcategory->category_id,
                          'sub_cat_id' => $subcategory->id,
                          'product_id' => $products->id,

                      ]);
                      $added_categories[] = $subcategory->category_id;
                  }
              }

              if(!empty($request->category_id)){
                  $reqcat=$request->category_id;
                  $remaining_ids=array_diff($reqcat,$added_categories);
                  //return $remaining_ids;
                  foreach($remaining_ids as $catid)
                      CategoryProduct::create([
                          'category_id' => $catid,
                          'sub_cat_id' =>null,
                          'product_id' => $products->id,

                      ]);
              }

              if($request->image){
                  $products->saveImage($request->image, 'products');
              }

          }

         return redirect()->route('product.edit', ['id'=>$products->id])->with('success', 'Product has been created');

          }

    public function edit(Request $request,$id){
             $products = Product::findOrFail($id);
             //$sizeprice=Size::where('product_id',$id)->get();
             $categories=Category::active()->get();
             $subcategories=SubCategory::active()->get();

            $documents = $products->sizeprice;
          //  return $documents;
             return view('admin.product.edit',['products'=>$products,'categories'=>$categories,'subcategories'=>$subcategories,'documents'=>$documents]);
             }
    public function Ajaxsubcat($id)
    {
        //var_dump($id);die;
        $subcat = SubCategory::active()
            ->where("category_id",$id)
            ->pluck("name","id");

        return json_encode($subcat);
    }

    public function update(Request $request,$id){
             $request->validate([
                 'isactive'=>'required',
                 'name'=>'required',
                 'description'=>'required',
                 'company'=>'required',
                 'stock_type'=>'required',
                 'min_qty'=>'required',
                 'max_qty'=>'required',
                 'stock'=>'required',
                 'image'=>'image',
                 'price'=>'required',
                 'cut_price'=>'required',
                 'cgst'=>'required',
                 'sgst'=>'required',
                 'can_be_subscribed'=>'required',
                 'subscription_cashback'=>'required',
                 'eligible_goldcash'=>'required',
                 'delivery_charge'=>'required'
                               ]);

             $products = Product::findOrFail($id);

			 $products->update([
                 'name'=>$request->name,
                 'description'=>$request->description,
                 'company'=>$request->company,
                 'stock_type'=>$request->stock_type,
                 'min_qty'=>$request->min_qty,
                 'max_qty'=>$request->max_qty,
                    'stock'=>$request->stock,
                 'isactive'=>$request->isactive,
                 'price'=>$request->price,
                 'cut_price'=>$request->cut_price,
                 'cgst'=>$request->cgst,
                 'sgst'=>$request->sgst,
                 'can_be_subscribed'=>$request->can_be_subscribed,
                 'subscription_cashback'=>$request->subscription_cashback,
                 'eligible_goldcash'=>$request->eligible_goldcash,
                 'delivery_charge'=>$request->delivery_charge,
             ]);
        $added_categories=[];

        CategoryProduct::where('product_id', $products->id)->delete();

        if(!empty($request->sub_cat_id)){
            $subcat=SubCategory::with('category')
                ->whereIn('id', $request->sub_cat_id)
                ->get();

            foreach($subcat as $subcategory) {
                CategoryProduct::create([
                    'category_id' => $subcategory->category_id,
                    'sub_cat_id' => $subcategory->id,
                    'product_id' => $products->id,

                ]);
                $added_categories[] = $subcategory->category_id;
            }
        }

        if(!empty($request->category_id)){
            $reqcat=$request->category_id;
            $remaining_ids=array_diff($reqcat,$added_categories);
            //return $remaining_ids;
            foreach($remaining_ids as $catid)
                CategoryProduct::create([
                    'category_id' => $catid,
                    'sub_cat_id' =>null,
                    'product_id' => $products->id,

                ]);
        }

        if($request->image){
                 $products->saveImage($request->image, 'products');
             }
           return redirect()->back()->with('success', 'Product has been updated');


      }


      public function document(Request $request, $id){
                     $request->validate([
                               'image.*'=>'image'
                               ]);
          $size=Size::find($request->size_id);
              //  var_dump($size);die();

              foreach($request->image as $file){

                 $img= ProductImage::create([
                      'size_id' => $request->size_id,
                      'product_id' => $id,
//                      'entity_id' => $request->size_id,
//                      'entity_type' => 'App\Models\ProductImage',
                      'image' => '11',
                      'product_id' => $id,

                  ]);

                  $img->saveImage($file, 'sizeimage');
                  }
             if($size)  {
                   return redirect()->back()->with('success', 'Product has been created');
                     }
                   return redirect()->back()->with('error', 'Product create failed');
          }

     public function delete(Request $request, $id){
         ProductImage::where('id', $id)->delete();
           return redirect()->back()->with('success', 'Document has been deleted');
        }

    public function sizeprice(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'size'=>'required',
            'price'=>'required',
            'stock'=>'required',
            'consumed_units'=>'required',
            'min_qty'=>'required',
            'max_qty'=>'required',
            'cut_price'=>'required',
            'cut_price'=>'required',
            'image'=>'required|image',
        ]);
        if($products=Size::create([
            'size'=>$request->size,
            'price'=>$request->price,
            'min_qty'=>$request->min_qty,
            'max_qty'=>$request->max_qty,
            'stock'=>$request->stock,
            'consumed_units'=>$request->consumed_units,
            'product_id'=>$id,
            'cut_price'=>$request->cut_price,
            'isactive'=>$request->isactive
        ]))
        {
            if($request->image){
                $products->saveImage($request->image, 'products');
            }
            return redirect()->back()->with('success', 'Product sizeprice has been created');
        }
        return redirect()->back()->with('error', 'Product sizeprice create failed');
    }

    public function updatesizeprice(Request $request){
        //var_dump($request->file);die;
        $request->validate([
            'isactive'=>'required',
            'price'=>'required',
            'stock'=>'required',
            'min_qty'=>'required',
            'max_qty'=>'required',
            'consumed_units'=>'required',
            'cut_price'=>'required',
        ]);

        $product = Size::findOrFail($request->size_id);
        $product->update([
            'size'=>$request->size,
            'price'=>$request->price,
            'cut_price'=>$request->cut_price,
            'min_qty'=>$request->min_qty,
            'max_qty'=>$request->max_qty,
            'stock'=>$request->stock,
            'consumed_units'=>$request->consumed_units,
            'isactive'=>$request->isactive,
        ]);
        {
            if($request->file){
                $product->saveImage($request->file, 'products');
            }

            return redirect()->back()->with('success', 'Product sizeprice has been created');
        }
        return redirect()->back()->with('error', 'Product sizeprice create failed');
    }

    public function allimages(Request $request)
    {
       // var_dump($request->size_id);die;
        $proimges = ProductImage::where("size_id",$request->size_id)->get();
        //var_dump($proimges);die;
            //->pluck("image","id");

        return json_encode($proimges);
    }

    public function productcategory(Request $request,$id){
        $request->validate([
            'category_id'=>'required',
            'sub_cat_id'=>'required',
        ]);
        if($products_with_category=CategoryProduct::create([
            'category_id'=>$request->category_id,
            'sub_cat_id'=>$request->sub_cat_id,
            'product_id'=>$id,
        ]))
        {

            return redirect()->back()->with('success', 'Product category  has been created');
        }
        return redirect()->back()->with('error', 'Product category create failed');
    }


    public function bulk_upload_form(Request $request){

         return view('admin.product.bulk-upload');

    }


    public function bulk_upload(Request $request){

         //var_dump($request->images);die;

         $request->validate([
             'name'=>'required',
             'company'=>'required',
             'description'=>'required',
             'isactive'=>'required|in:0,1',
             'stock_type'=>'required|in:packet,quantity',
             'stock'=>'required|integer|min:0',
             'is_offer'=>'required|integer|min:0',
              'size'=>'required',
             'price'=>'required|integer',
             'cut_price'=>'required|integer',
             //'size_stock'=>'required|integer',
             'min_qty'=>'required|integer|min:1',
             'max_qty'=>'required|integer|min:1',
             'consumed_units'=>'required|integer|min:1',
             'is_size_active'=>'required|in:0,1',
         ]);

         $product=Product::where(DB::raw('BINARY name'), $request->name)
             ->where('company', $request->company)
             ->first();
         if($product){
             $product->update(array_merge($request->only('company', 'description', 'isactive', 'stock_type', 'stock', 'is_offer'), ['is_hotdeal'=>$request->hot_deal, 'is_newarrival'=>$request->new_arrival, 'is_discounted'=>$request->discounted]));
         }else{
             $product=Product::create(array_merge($request->only('name', 'company', 'description', 'isactive', 'stock_type', 'stock', 'is_offer'), ['is_hotdeal'=>$request->hot_deal, 'is_newarrival'=>$request->new_arrival, 'is_discounted'=>$request->discounted]));
         }

        CategoryProduct::where('product_id', $product->id)->delete();

        $added_categories=[];
        if($request->sub_category){
            $subcategories=explode(',', $request->sub_category);
            $filtered_sub=[];
            foreach($subcategories as $s){
                $s=trim($s);
                if(!empty($s))
                    $filtered_cat[]=$s;
            }
            if(!empty($filtered_sub)){
                $subcategories=SubCategory::active()->whereIn('name', $filtered_sub)->get();
                foreach($subcategories as $sub) {
                    CategoryProduct::create([
                        'category_id' => $sub->category_id,
                        'sub_cat_id' => $sub->id,
                        'product_id' => $product->id,

                    ]);
                    $added_categories[] = $sub->category_id;
                }
            }
        }

        if($request->category){
            $categories=explode(',', $request->category);
            $filtered_cat=[];
            foreach($categories as $s){
                $s=trim($s);
                if(!empty($s))
                    $filtered_cat[]=$s;
            }
            if(!empty($filtered_cat)){
                $categories=Category::active()
                    ->whereIn('name', $filtered_cat)
                    ->get();
                $req_cats=[];
                foreach($categories as $cat) {
                    $req_cats[]=$cat->id;
                }
                if($req_cats){
                    $remaining_ids=array_diff($req_cats,$added_categories);
                    foreach($remaining_ids as $r){
                        CategoryProduct::create([
                            'category_id' => $r,
                            'sub_cat_id' => null,
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }

        }

        if($product){
            $size=Size::where('product_id', $product->id)
                ->where(DB::raw('BINARY size'), $request->size)
                ->first();
            if($size){
                $size->update(array_merge($request->only('price', 'cut_price', 'consumed_units', 'min_qty', 'max_qty', 'is_offer'), ['stock'=>$request->stock, 'isactive'=>$request->is_size_active]));
            }else{
                $size=Size::create(array_merge($request->only('size', 'price', 'cut_price', 'consumed_units', 'min_qty', 'max_qty', 'is_offer'), ['product_id'=>$product->id, 'stock'=>$request->stock, 'isactive'=>$request->is_size_active]));
            }
        }

         if($size){
            if($request->images){

                foreach($request->images as $image){

                        $img= ProductImage::create([
                            'size_id' => $size->id,
                            'product_id' => $product->id,
                            'image' => '11',

                        ]);
                        $img->saveImage($image, 'sizeimage');

                        $img->refresh();
                        if(empty($size->image)){
                            $size->image=$img->getOriginal('image');
                            $size->save();
                        }

                }

            }
         }


    }



}
