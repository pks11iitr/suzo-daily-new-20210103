@extends('layouts.admin')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('orders.list')}}">Order </a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    {{--<div class="card-header">
                        <h3 class="card-title">Order Detail</h3>
                    </div>--}}
                    <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Order Details</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><b>RefId</b></td><td>{{$order->refid}}</td>
                                    <td><b>Date & Time</b></td><td>{{$order->created_at}}</td>
                                </tr>
                                <tr>
                                    <td><b>Rider Name</b></td><td>{{$order->rider->name??''}}
                                        <a href="{{route('order.details',['id'=>$order->id])}}" class="open-RiderChange btn btn-success" data-toggle="modal" data-target="#exampleModal" data-id="{{$order->id}}">Change Rider</a></td>
                                    <td><b>Total</b></td>
                                    <td>{{$order->total_cost}}</td>
                                </tr>

                                <tr>
                                    <td><b>Delivery Charge</b></td>
                                    <td>{{$order->delivery_charge}}</td>
                                    <td><b>Coupon Discount</b></td><td>{{$order->coupon_discount }}</td>
                                </tr>
                                <tr>
                                    <td><b>Cashback Redeemed Discount</b></td><td>{{$order->points_used }}</td>
                                    <td><b>Wallet Balance Used Discount</b></td><td>{{$order->balance_used }}</td>
                                </tr>
                                <tr>
                                    <td><b>Coupon Applied</b></td><td>{{$order->coupon_applied}}</td>
                                    <td></td><td></td>
                                </tr>
                                <tr>
                                    <td><b>Payment Status</b></td><td>{{$order->payment_status}} &nbsp; @if(in_array($order->payment_status, ['payment-wait']))
                                            <a href="{{route('payment.status.change', ['id'=>$order->id,'status'=>'paid'])}}" name='status' class="btn btn-primary">Mark As Paid</a>
                                        @endif</td>

                                    <td><b>Payment Mode</b></td><td>{{$order->payment_mode}}</td>

                                </tr>
                                <tr>
                                    <td>
                                        Eligible GoldCash
                                    </td>
                                    <td><></td>
                                    <td><b>Add/Revoke Cashback/Wallet Balance</b><br></td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="openWalletPanel('{{$order->id}}', '{{route('user.wallet.balance', ['id'=>$order->user_id])}}')">Open Panel</a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <a href="{{route('user.wallet.history', ['id'=>$order->user_id])}}" target="_blank">Wallet History</a>

                                    </td>

                                </tr>
                                <tr>


                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Slot</th>
                                    <th>Sch./Del.</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td>{{$detail->type}}</td>
                                        <td>{{$detail->product->name??''}}</td>
                                        <td>{{$detail->total_quantity??0}}</td>
                                        <td>{{$detail->price??0}}</td>
                                        <td>{{($detail->price??0)*($detail->total_quantity??0)}}</td>
                                        <td>{{$detail->start_date}}/{{$detail->timeslot->name}}</td>
                                        <td>{{$detail->scheduled_quantity}}/{{$detail->delivered_quantity}}</td>
                                        <td>{{$detail->status}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Returned Products</th>
                                    {{--<th></th>--}}
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->returned as $detail)
                                    <tr>
                                        <td>{{$detail->name??''}}</td>
                                        <td>{{$detail->quantity}}</td>
                                        <td>Rs. {{$detail->price}}/Item</td>
                                        <td>{{$detail->price}}/Item</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            Return Reason: {{$order->return_reason}}
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Customer Details</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Name</td><td>{{$order->deliveryaddress->first_name??''}} {{$order->deliveryaddress->last_name??''}}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td><td>{{$order->deliveryaddress->mobile_no??''}}</td>
                                </tr>
                                <tr>
                                    <td>Email</td><td>{{$order->deliveryaddress->email??''}}</td>
                                </tr>
                                <tr>
                                    <td>Address</td><td>{{$order->deliveryaddress->house_no??''}},{{$order->deliveryaddress->appertment_name??''}}, {{$order->deliveryaddress->street??''}}, {{$order->deliveryaddress->landmark??''}}, {{$order->deliveryaddress->	area??''}}, {{$order->deliveryaddress->city??''}}, {{$order->deliveryaddress->pincode??''}}, {{$order->deliveryaddress->address_type??''}}</td>
                                </tr>
                                <tr>
                                    <td>Map Address</td><td>{{$order->deliveryaddress->map_address??''}}</td>
                                </tr>
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Rider</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form role="form" method="post" enctype="multipart/form-data"  action="{{route('rider.change',['id'=>$order->id])}}">
                                @csrf
                                <input type="hidden" name="orderid" class="form-control" id="orderid">
                                <div class="form-group">
                                    <label for="exampleInputtitle">Rider Name</label>
                                    <select name="riderid" class="form-control" id="riderid" placeholder="" >
                                        @foreach($riders as $rider)
                                            <option value="{{$rider->id}}"
                                                {{$order->rider_id==$rider->id?'selected':''}}>{{$rider->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" type="submit">Change</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->

    </div>

    <div class="modal fade show" id="modal-lg" style="display: none; padding-right: 15px;" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Remove Cashback/Wallet Balance&nbsp;&nbsp;&nbsp;&nbsp;Balance:<span id="user-wallet-balance"></span>&nbsp;&nbsp;Cashback:<span id="user-wallet-cashback"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="booking-form-section">
                    <form role="form" method="post" enctype="multipart/form-data" action="{{route('wallet.add.remove')}}">
                        @csrf
                        <input type="hidden" name="order_id" id="wallet-order-id" value="1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Select Add/Revoke</label>
                                        <select class="form-control" name="action_type" required="">
                                            <option value="">Select Any</option>
                                            <option value="add">Add</option>
                                            <option value="revoke">Revoke</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Calculation Type</label>
                                        <select class="form-control" name="calculation_type" required="">
                                            <option value="">Select Any</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Type(Cashback/Wallet Balance)</label>
                                        <select class="form-control" name="amount_type" required="">
                                            <option value="">Select Any</option>
                                            <option value="cashback">Cashback</option>
                                            <option value="balance">Wallet Balance</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Amount</label>
                                        <input type="number" name="amount" class="form-control" required="" value="0.0" min="0.01" step=".01">
                                    </div>

                                </div>


                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Description</label>
                                <input type="text" name="wallet_text" class="form-control" required="" placeholder="Max 150 characters">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                {{--                <div class="modal-footer justify-content-between">--}}
                {{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                {{--                </div>--}}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    <script>

        function openWalletPanel(id, url){
                    $("#wallet-order-id").val(id)
                    $.ajax({
                        url:url,
                        method:'get',
                        datatype:'json',
                        success:function(data){
//alert(data)
                            if(data.status=='success'){
//alert()
                                $("#user-wallet-balance").html(data.data.balance)
                                $("#user-wallet-cashback").html(data.data.cashback)

                            }

                        }
                    })
                    $("#modal-lg").modal('show')

        }


        $(document).on("click", ".open-RiderChange", function () {
            var myBookId = $(this).data('id');
            $(".modal-body #orderid").val( myBookId );

        });

    </script>

@endsection
