<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
//use App\Models\OfferCategory;
use App\Models\OfferDetail;
use App\Models\Product;
use App\Models\SpecialCategory;
use App\Models\SpecialCategoryProduct;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class BannerController extends Controller
{
     public function index(Request $request){
            $banners=Banner::with('entity')->get();
            return view('admin.banner.view',['banners'=>$banners]);
              }

    public function create(Request $request){
             $categorys = Category::get();
             $subcategorys = SubCategory::get();
             $offercategorys = SpecialCategory::get();
             $offers=OfferDetail::get();

            return view('admin.banner.add',['categorys'=>$categorys,'subcategorys'=>$subcategorys,'offercategorys'=>$offercategorys, 'offers'=>$offers]);

               }

   public function store(Request $request){
               $request->validate([
                  			'isactive'=>'required',
                  			'image'=>'required|image'
                               ]);
               return $request->all();

       if(stripos($request->entity_type, 'subcat_')!==false){
           $id=str_replace('subcat_', '', $request->entity_type);
           $subcategory=SubCategory::find((int)$id);
           $entitytype='App\Models\SubCategory';
           $entitytid=$subcategory->id;
           $main_id=$subcategory->category_id??'';

       }elseif(stripos($request->entity_type, 'cat_')!==false){
           $id=str_replace('cat_', '', $request->entity_type);
           $category=Category::find((int)$id);
           $entitytype='App\Models\Category';
           $entitytid=$category->id;
           $main_id=null;
       }
       elseif(stripos($request->entity_type, 'offer_')===0){
           $id=str_replace('offer_', '', $request->entity_type);
           $offercategory=SpecialCategory::find((int)$id);
           $entitytype='App\Models\SpecialCategory';
           $entitytid=$offercategory->id;
           $main_id=null;
       }
       elseif(stripos($request->entity_type, 'detailedoffer_')!==false){
           $id=str_replace('detailedoffer_', '', $request->entity_type);
           $offercategory=OfferDetail::find((int)$id);
           $entitytype='App\Models\OfferDetail';
           $entitytid=$offercategory->id;
           $main_id=null;
       }
       else{
           $entitytype=null;
           $entitytid=null;
           $main_id=null;
       }

          if($banner=Banner::create([
                      'isactive'=>$request->isactive,
                        'type'=>$request->type,
                      'entity_type'=>$entitytype,
                      'entity_id'=>$entitytid,
                      'parent_id'=>$main_id,
                      'image'=>'a']))
            {
				$banner->saveImage($request->image, 'banners');
             return redirect()->route('banners.list', ['id'=>$banner->id])->with('success', 'Banner has been created');
            }
             return redirect()->back()->with('error', 'Banner create failed');
          }

    public function edit(Request $request,$id){
             $banner = Banner::findOrFail($id);
             $categorys = Category::get();
             $subcategorys = SubCategory::get();
             $offercategorys = SpecialCategory::get();
            $products =Product::active()->get();
            $special_category =SpecialCategoryProduct::where('category_id',$id)->get();
            $offers =OfferDetail::get();

             return view('admin.banner.edit',['banner'=>$banner,'categorys'=>$categorys,'subcategorys'=>$subcategorys,'offercategorys'=>$offercategorys,'products'=>$products,'special_category'=>$special_category, 'offers'=>$offers]);
             }

    public function update(Request $request,$id){
             $request->validate([
                            'isactive'=>'required',
                            'image'=>'image'
                                   ]);
             $banner = Banner::findOrFail($id);

        if(stripos($request->entity_type, 'subcat_')!==false){
            $id=str_replace('subcat_', '', $request->entity_type);
            $subcategory=SubCategory::find((int)$id);
            $entitytype='App\Models\SubCategory';
            $entitytid=$subcategory->id;
            $main_id=$subcategory->category_id??'';

        }elseif(stripos($request->entity_type, 'cat_')!==false){
            $id=str_replace('cat_', '', $request->entity_type);
            $category=Category::find((int)$id);
            $entitytype='App\Models\Category';
            $entitytid=$category->id;
            $main_id=null;
        }
        elseif(stripos($request->entity_type, 'offer_')!==false){
            $id=str_replace('offer_', '', $request->entity_type);
            $offercategory=SpecialCategory::find((int)$id);
            $entitytype='App\Models\SpecialCategory';
            $entitytid=$offercategory->id;
            $main_id=null;
        }
        else{
            $entitytype=null;
            $entitytid=null;
            $main_id=null;
        }

        if($banner->update([
            'isactive'=>$request->isactive,
            'type'=>$request->type,
            'entity_type'=>$entitytype,
            'entity_id'=>$entitytid,
            'parent_id'=>$main_id
        ])){

          if($request->image){
              $banner->saveImage($request->image, 'banners');

        }
           return redirect()->route('banners.list')->with('success', 'Banner has been updated');
        }
           return redirect()->back()->with('error', 'Banner update failed');

      }

    public function delete(Request $request, $id){
           Banner::where('id', $id)->delete();
           return redirect()->back()->with('success', 'Banner has been deleted');
        }


        public function specialProduct(Request $request,$id){
            $request->validate([
                'product_id'=>'required',
            ]);

            $banner_category = Banner::findOrFail($id);

            if($special_category=SpecialCategoryProduct::create([
                'category_id'=>$banner_category->id,
                'product_id'=>$request->product_id,
            ]))

            {
                return redirect()->back()->with('success', 'Special Category Product has been created');
            }
            return redirect()->back()->with('error', 'Special Category Product create failed');

        }

        public function productDelete(Request $request,$id){

            SpecialCategoryProduct::where('id', $id)->delete();

            return redirect()->back()->with('success', 'Special Category Product has been deleted');
        }

  }
