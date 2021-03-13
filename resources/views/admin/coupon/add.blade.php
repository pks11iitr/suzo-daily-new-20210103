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
                            <li class="breadcrumb-item active"><a href="{{route('coupon.list')}}">Coupon</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Coupon Add</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('coupon.store')}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Code</label>
                                                <input type="text" name="code" class="form-control" id="exampleInputimage" placeholder="Enter Code">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Discount</label>
                                                <input type="number" name="discount" class="form-control" id="exampleInputimage" placeholder="Enter Discount" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputistop">Discount Type</label>
                                                <select name="discount_type" class="form-control" id="exampleInputistop" placeholder="">
                                                    <option value="">Please Select Type</option>
                                                    <option value="Fixed">Fixed</option>
                                                    <option value="Percent">Percent</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputistop">Is Active</label>
                                                <select name="isactive" class="form-control" id="exampleInputistop" placeholder="">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Minimum Order</label>
                                                <input type="number" name="minimum_order" class="form-control" id="exampleInputimage" placeholder="Enter Minimum Order" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Maximum Discount</label>
                                                <input type="number" name="maximum_discount" class="form-control" id="exampleInputimage" placeholder="Enter Maximum Discount" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Expiry Date</label>
                                                <input type="date" name="expiry_date" class="form-control" id="exampleInputimage" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputistop">Usage Type</label>
                                                <select name="usage_type" class="form-control" id="exampleInputistop" placeholder="">
                                                    <option value="">Please Select Usage Type</option>
                                                    <option value="single-singleuser">One User Can Use One Time</option>
                                                    <option value="single-multipleuser">Many User Can Use One Time</option>
                                                    <option value="multiple-multipleuser">Many User Can Use Many Time</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->
    </div>
@endsection
