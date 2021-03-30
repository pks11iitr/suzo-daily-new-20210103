<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use PDF;
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

    public function download($id){
        $orders = Order::with(['details', 'customer', 'deliveryaddress'])->find($id);
        // var_dump($orders);die();
        $invoice=Invoice::find(1);
        $pdf = PDF::loadView('admin.order.newinvoice', compact('orders','invoice'))->setPaper('a4', 'portrait');
        return $pdf->download('invoice.pdf');
        //return view('admin.contenturl.newinvoice',['orders'=>$orders, 'invoice'=>$invoice]);
    }

}
