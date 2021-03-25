@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Order</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Order</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        @if(auth()->user()->hasRole('admin'))
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Invoice Number Setup</h3>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form class="form-validate form-horizontal"  method="post" action="{{route('order.invoice.update',['id'=>$invoice->id])}}" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <label>Organization Name</label>
                                                            <input type="text" name="organization_name" class="form-control" id="exampleInputEmail1" placeholder="Please Type" value="{{$invoice->organization_name}}">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Prifix</label>
                                                            <input type="text" name="prefix" class="form-control" id="exampleInputEmail1" placeholder="Enter Prifix" value="{{$invoice->prefix}}">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Sequence</label>
                                                            <input type="number" name="sequence" class="form-control" min="0" id="exampleInputEmail3" placeholder="Enter Sequence" value="{{$invoice->sequence}}">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Current Sequence</label>
                                                            <input type="number" name="current_sequence" class="form-control" min="0" id="exampleInputEmail3" placeholder="Enter Current Sequence" value="{{$invoice->current_sequence??1}}">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>PAN/GST</label>
                                                            <input type="text" name="pan_gst" class="form-control" min="0" id="exampleInputEmail3" placeholder="Enter PAN/GST" value="{{$invoice->pan_gst}}">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Address</label>
                                                            <input type="text" name="address" class="form-control" min="0" id="exampleInputEmail3" placeholder="Enter Address" value="{{$invoice->address??''}}">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Image</label>
                                                            <input type="file" name="image" class="form-control" id="exampleInputEmail3">
                                                        </div>
                                                        <div class="col-3">
                                                            <label>View Image</label>
                                                            <img src="{{$invoice->image??''}}" width="200px" height="100px">
                                                        </div>
                                                        <div class="col-3">
                                                            <textarea name="t_n_c" class="form-control" >{{$invoice->t_n_c}}</textarea>
                                                        </div>
                                                        <div class="col-3"><label>.</label><br>
                                                            <button type="submit" name="save" class="btn btn-danger">Update</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12">

                                        <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">

                                            <div class="row">
                                                <div class="col-4">
                                                    <input  id="fullname"  class="form-control" name="search" placeholder=" search name/email/mobile/order id" value="{{request('search')}}"  type="text" />
                                                </div>
                                                <div class="col-4">

                                                    <select id="store_id" name="store_id" class="form-control" >
                                                        <option value="" {{ request('store_id')==''?'selected':''}}>Select Store</option>
                                                        @foreach($stores as $store)
                                                            <option value="{{$store->id}}" {{request('store_id')==$store->id?'selected':''}}>{{ $store->name }}</option>                                    @endforeach

                                                    </select>

                                                </div>
                                                <div class="col-4">

                                                    <select id="rider_id" name="rider_id" class="form-control" >
                                                        <option value="" {{ request('store_id')==''?'selected':''}}>Select Rider</option>
                                                        @foreach($riders as $rider)
                                                            <option value="{{$rider->id}}" {{request('rider_id')==$rider->id?'selected':''}}>{{ $rider->name }}</option>                                    @endforeach

                                                    </select>

                                                </div><br><br>

                                                <div class="col-4">

                                                    <select id="status" name="status" class="form-control" >

                                                        <option value="" {{ request('status')==''?'selected':''}}>Please select</option>
                                                        <option value="pending" {{ request('status')=='confirmed'?'selected':''}}>New Order</option>
                                                        <option value="confirmed" {{ request('status')==='dispatched'?'selected':''}}>Dispatched</option>
                                                        <option value="cancelled" {{ request('status')=='cancelled'?'selected':''}}>Cancelled</option>
                                                        <option value="cancelled" {{ request('status')=='completed'?'selected':''}}>Completed</option>
                                                    </select>

                                                </div><br><br>
                                                <div class="col-4">
                                                    <select id="payment_status" name="payment_status" class="form-control" >

                                                        <option value="" {{ request('payment_status')==''?'selected':''}}>Select Payment Status</option>
                                                        <option value="paid" {{ request('payment_status')=='paid'?'selected':''}}>Paid</option>
                                                        <option value="payment-wait" {{ request('payment_status')==='payment-wait'?'selected':''}}>Pending</option>

                                                    </select>

                                                </div><br><br>

                                                <div class="col-4">

                                                    <select id="delivery_slot" name="delivery_slot" class="form-control" >
                                                        <option value="" {{ request('delivery_slot')==''?'selected':''}}>Select Time Slot</option>
                                                        @foreach($timeslots as $timeslot)
                                                            <option value="{{$timeslot->id}}" {{request('delivery_slot')==$timeslot->id?'selected':''}}>{{ $timeslot->name }}</option>                                    @endforeach

                                                    </select>

                                                </div>

                                                <div class="col-4">
                                                    <input  id="fullname"  class="form-control" name="fromdate" placeholder=" search name" value="{{request('fromdate')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <input  id="fullname"  class="form-control" name="todate" placeholder=" search name" value="{{request('todate')}}"  type="date" />
                                                </div>

                                                <div class="col-4">
                                                    <select id="payment_mode" name="payment_mode" class="form-control" >

                                                        <option value="" {{ request('payment_mode')==''?'selected':''}}>Select Payment Mode</option>
                                                        <option value="COD" {{ request('payment_mode')=='COD'?'selected':''}}>COD</option>
                                                        <option value="online" {{ request('payment_mode')=='online'?'selected':''}}>Online</option>

                                                    </select>

                                                </div><br><br>

                                                <div class="col-4">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                                                    <a href="{{route('orders.list')}}" class="btn btn-danger">Reset Filters</a>
                                                </div><br><br>
                                                <div class="col-4">
                                                    <a class="btn btn-primary" href="{{route('sales.report')}}?{{Request::getQueryString()}}">Download Sales Report</a>
                                                </div>
                                                <div class="col-4">
                                                    <a class="btn btn-primary" href="{{route('order.report')}}?{{Request::getQueryString()}}">Download Order Report</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>OrderID</th>
                                        <th>User</th>
                                        <th>Store Name</th>
                                        <th>Rider Name</th>
                                        <th>Order Date</th>
                                        <th>Delivery Slot</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                        {{--                                        <th>Payment Status</th>--}}
                                        <th>Payment Mode</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{$order->refid}}</td>
                                            <td>{{$order->customer->name??''}} <br>Mob: {{$order->customer->mobile??''}}</td>

                                            <td>{{$order->storename->name??''}}</td>
                                            <td>{{$order->rider->name??''}}</td>
                                            <td>{{date('d/m/Y H:i A', strtotime($order->created_at))}}</td>
                                            <td>@if($order->is_express_delivery)
                                                    Express Delivery
                                                @else
                                                    {{date('d/m/Y', strtotime($order->delivery_date))}} {{$order->timeslot->name??''}}

                                                @endif                                                       </td>
                                            <td>{{$order->total_cost+$order->delivery_charge}}</td>
                                            <td>{{$order->status}}</td>
                                            {{--                                                <td>{{$order->payment_status}}</td>--}}
                                            <td>{{$order->payment_mode}}</td>
                                            <td>
                                                <a href="{{route('order.details',['id'=>$order->id])}}" class="btn btn-primary">Details</a><br><br>
                                                <a href="{{route('download.invoice',['id'=>$order->id])}}" class="btn btn-warning">Invoice</a>
                                            </td>
                                        </tr
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        {{$orders->links()}}
                        <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->
@endsection
