@extends('layouts.admin')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Delivery</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Delivery</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
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
{{--                                                <div class="col-3">--}}
{{--                                                    <select name="user_id" class="form-control" >--}}
{{--                                                        <option value="" {{ request('user_id')==''?'selected':''}}>Select Store Name</option>--}}
{{--                                                        @foreach($stores as $store)--}}
{{--                                                            <option value="{{$store->id}}" {{request('user_id')==$store->id?'selected':''}}>{{ $store->name }}</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}
                                                <div class="col-3">
                                                    <select name="rider_id" class="form-control" >
                                                        <option value="" {{ request('rider_id')==''?'selected':''}}>Select Rider Name</option>
                                                        @foreach($riders as $rider)
                                                            <option value="{{$rider->id}}" {{request('rider_id')==$rider->id?'selected':''}}>{{ $rider->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <input   class="form-control" name="fromdate" placeholder=" search name" value="{{request('fromdate')}}"  type="date" />
                                                </div>
                                                <div class="col-3">
                                                    <input  class="form-control" name="todate" placeholder=" search name" value="{{request('todate')}}"  type="date" />
                                                </div><br><br>
                                                <div class="col-3">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
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
                                        <th>Customer</th>
                                        <th>Store</th>
                                        <th>Rider</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Delivery Date</th>
                                        <th>Status</th>
                                        <th>Not Accepted Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deliveries as $delivery)
                                        <tr>
                                            <td>
                                                <b>Name :</b> {{$delivery->customer->name??''}}<br>
                                                <b>Mobile :</b> {{$delivery->customer->mobile??''}}
                                                <b>Address:</b>{{$delivery->deliveryaddress->street??''}}
                                            </td>
                                            <td>{{$delivery->store->name??''}}</td>
                                            <td>{{$delivery->rider->name??''}}</td>
                                            <td>{{$delivery->product->name??''}}</td>
                                            <td>{{$delivery->quantity}}</td>
                                            <td>{{$delivery->delivery_date}}/{{$delivery->timeslot->name}}</td>
                                            <td>{{$delivery->status}}</td>
                                            <td>{{$delivery->quantity_not_accepted}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{$deliveries->appends(request()->query())->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- ./wrapper -->
@endsection



