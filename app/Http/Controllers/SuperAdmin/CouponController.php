<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Coupon;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index(Request $request){
        $coupons =Coupon::get();
        return view('admin.coupon.view',['coupons'=>$coupons]);
    }

    public function create(Request $request){
        return view('admin.coupon.add');
    }

    public function store(Request $request){
        Coupon::create($request->only(['code','discount_type','minimum_order', 'discount', 'isactive','usage_type','maximum_discount','expiry_date']));
        {
            return redirect()->route('coupon.list')->with('success', 'Coupon has been created');
        }
            return redirect()->back()->with('error', 'Coupon failed');
    }

    public function edit(Request $request,$id){
        $coupon =Coupon::findOrFail($id);
        $subcategories=SubCategory::get();
        return view('admin.coupon.edit',['coupon'=>$coupon, 'subcategories'=>$subcategories]);
    }

    public function update(Request $request,$id){
        $coupon =Coupon::findOrFail($id);
        $coupon->update($request->only(['code','discount_type','minimum_order', 'discount', 'isactive','usage_type','maximum_discount','expiry_date']));

        $coupon->categories()->sync($request->sub_categories);

        return redirect()->route('coupon.list')->with('success', 'Coupon has been updated');

    }
}
