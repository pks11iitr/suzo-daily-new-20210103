<?php


namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductsExport implements FromView
{
    public $products;

    public function __construct($data){

        $this->products=$data;
        //return $this->products;
    }
    public function view(): View
    {

        //dd($this->products);

        return view('admin.product.exports', [
            'products' => $this->products
        ]);
    }
}
