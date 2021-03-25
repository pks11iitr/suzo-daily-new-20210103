<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function update(Request $request,$id){
        $request->validate([
            'prefix'=>'required',
            'sequence'=>'required',
            'organization_name'=>'required',
        ]);

        $invoice =Invoice::findOrFail($id);

        $invoice->update([
            'prefix'=>$request->prefix,
            'sequence'=>$request->sequence,
            'address'=>$request->address,
            'current_sequence'=>$request->current_sequence??1,
            'pan_gst'=>$request->pan_gst,
            'organization_name'=>$request->organization_name,
            't_n_c'=>$request->t_n_c
        ]);
        if($request->image){
            $invoice->saveImage($request->image, 'invoice');
        }

        if($invoice) {
            return redirect()->back()->with('success', 'Invoice has been updated');
        }
        return redirect()->back()->with('error', 'Invoice update failed');
    }

}
