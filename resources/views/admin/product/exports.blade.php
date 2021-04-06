<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Company</th>
        <th>Stock</th>
        <th>Active(0/1)</th>
        <th>Category</th>
        <th>Subcategory</th>
        <th>Price</th>
        <th>SGST(%)</th>
        <th>CGST(%)</th>
        <th>Cut Price</th>
        <th>Min. Quantity</th>
        <th>Max. Quantity</th>
        <th>Can Be Subscribed</th>
        <th>Subscription Cashback</th>
        <th>Gold Cash</th>
        <th>Delivery Charge(subscription only)</th>
        <th>Image Identifier</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
            <tr>
                <td>{{ $product->name??'.' }}</td>
                <td>{{ $product->company??'.' }}</td>
                <td>{{ $product->stock??0 }}</td>
                <td>{{ $product->isactive??0 }}</td>
                <td>@if(count($product->category))
                        @foreach($product->category as $cat){{ $cat->name.'***' }}@endforeach
                    @else
                        {{'***'}}
                    @endif
                </td>
                <td>@if(count($product->subcategory))
                        @foreach($product->subcategory as $cat){{ $cat->name.'***' }}@endforeach
                    @else
                        {{'***'}}
                    @endif
                </td>
                <td>{{ $product->price??0 }}</td>
                <td>{{ $product->sgst??0 }}</td>
                <td>{{ $product->cgst??0 }}</td>
                <td>{{ $product->cut_price??0 }}</td>
                <td>{{ $product->min_qty??1 }}</td>
                <td>{{ $product->max_qty??500 }}</td>
                <td>{{ $product->can_be_subscribed??0 }}</td>
                <td>{{ $product->subscription_cashback??0 }}</td>
                <td>{{ $product->eligible_goldcash??0 }}</td>
                <td>{{ $product->delivery_charge??0 }}</td>
                <td>*</td>
            </tr>
    @endforeach
    </tbody>
</table>
