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
                                <h3 class="card-title">Coupon Edit</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('coupon.update',['id'=>$coupon->id])}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Code</label>
                                                <input type="text" name="code" class="form-control" id="exampleInputimage" placeholder="Enter Code" value="{{$coupon->code}}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Discount</label>
                                                <input type="number" name="discount" class="form-control" id="exampleInputimage" placeholder="Enter Discount" min="0" value="{{$coupon->discount}}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputistop">Discount Type</label>
                                                <select name="discount_type" class="form-control" id="exampleInputistop" placeholder="">
                                                    <option value="">Please Select Type</option>
                                                    <option value="Fixed" {{$coupon->discount_type=='Fixed'?'selected':''}}>Fixed</option>
                                                    <option value="Percent" {{$coupon->discount_type=='Percent'?'selected':''}}>Percent</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputistop">Is Active</label>
                                                <select name="isactive" class="form-control" id="exampleInputistop" placeholder="">
                                                    <option value="1" {{$coupon->isactive==1?'selected':''}}>Yes</option>
                                                    <option value="0" {{$coupon->isactive==0?'selected':''}}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Minimum Order</label>
                                                <input type="number" name="minimum_order" class="form-control" id="exampleInputimage" placeholder="Enter Minimum Order" min="0" value="{{$coupon->minimum_order}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Maximum Discount</label>
                                                <input type="number" name="maximum_discount" class="form-control" id="exampleInputimage" placeholder="Enter Maximum Discount" min="0" value="{{$coupon->maximum_discount}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputimage">Expiry Date</label>
                                                <input type="date" name="expiry_date" class="form-control" id="exampleInputimage" placeholder="" value="{{$coupon->expiry_date}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputistop">Usage Type</label>
                                                <select name="usage_type" class="form-control" id="exampleInputistop" placeholder="">
                                                    <option value="">Please Select Usage Type</option>
                                                    <option value="single-singleuser" {{$coupon->usage_type=='single-singleuser'?'selected':''}}>One User Can Use One Time</option>
                                                    <option value="single-multipleuser" {{$coupon->usage_type=='single-multipleuser'?'selected':''}}>Many User Can Use One Time</option>
                                                    <option value="multiple-multipleuser" {{$coupon->usage_type=='multiple-multipleuser'?'selected':''}}>Many User Can Use Many Time</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="exampleInputtitle">Sub Category</label>
                                                    <select class="form-control select2" multiple data-placeholder="Select a subcategory" style="width: 100%;" name="sub_categories[]">

                                                        <option value="">Please Select Category</option>
                                                        @foreach($subcategories as $subcategory)

                                                            <option value="{{$subcategory->id}}" @foreach($coupon->categories as $s) @if($s->id==$subcategory->id){{'selected'}}@endif @endforeach >{{$subcategory->name}}</option>

                                                        @endforeach
                                                    </select>
                                                </div>
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
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2();
            $('#category_id_sel').select2();
        });
    </script>
@endsection
