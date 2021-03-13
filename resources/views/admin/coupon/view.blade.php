@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Coupon</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Coupon</li>
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
                                <a href="{{route('coupon.create')}}" class="btn btn-primary">Add Coupon</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Discount Type</th>
                                        <th>Discount</th>
                                        <th>Minimum Order</th>
                                        <th>Maximum Discount</th>
                                        <th>Expiry Date</th>
                                        <th>Use Type</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($coupons as $coupon)
                                        <tr>
                                            <td>{{$coupon->code}}</td>
                                            <td>{{$coupon->discount_type}}</td>
                                            <td>{{$coupon->discount}}</td>
                                            <td>{{$coupon->minimum_order}}</td>
                                            <td>{{$coupon->maximum_discount	}}</td>
                                            <td>{{$coupon->expiry_date}}</td>
                                            <td>{{$coupon->usage_type}}</td>
                                            <td>
                                                @if($coupon->isactive==1){{'Yes'}}
                                                @else{{'No'}}
                                                @endif
                                            </td>
                                            <td><a href="{{route('coupon.edit',['id'=>$coupon->id])}}" class="btn btn-success">Edit</a>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
@endsection

