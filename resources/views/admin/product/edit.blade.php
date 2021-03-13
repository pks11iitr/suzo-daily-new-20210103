@extends('layouts.admin')
@section('content')
    <link rel="stylesheet" href="{{asset('../admin-theme/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet"
          href="{{asset('../admin-theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('product.list')}}">Product</a></li>
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
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Product Update</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data"
                                  action="{{route('product.update',['id'=>$products->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Name</label>
                                                <input type="text" name="name" class="form-control"
                                                       id="exampleInputEmail1" placeholder="Enter Name"
                                                       value="{{$products->name}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Description</label>
                                                <textarea type="text" name="description" class="form-control"
                                                          id="exampleInputEmail1"
                                                          placeholder="Enter Description">{{$products->description}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Company</label>
                                                <input type="text" name="company" class="form-control"
                                                       id="exampleInputEmail1" placeholder="Enter Company"
                                                       value="{{$products->company}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Rating</label>
                                                <input type="text" name="ratings" class="form-control"
                                                       id="exampleInputEmail1" placeholder="Enter Rating"
                                                       value="{{$products->ratings}}">
                                            </div>
                                        </div>
                                        {{--                            <div class="col-md-6">--}}
                                        {{--                  <div class="form-group">--}}
                                        {{--                    <label for="exampleInputEmail1">Min Qty</label>--}}
                                        {{--                    <input type="text" name="min_qty" class="form-control" id="exampleInputEmail1" placeholder="Enter Qty" value="{{$products->min_qty}}">--}}
                                        {{--                  </div>--}}
                                        {{--                  </div>--}}
                                        {{--                        <div class="col-md-6">--}}
                                        {{--                  <div class="form-group">--}}
                                        {{--                    <label for="exampleInputEmail1">Max Qty</label>--}}
                                        {{--                    <input type="text" name="max_qty" class="form-control" id="exampleInputEmail1" placeholder="Enter Qty" value="{{$products->max_qty}}">--}}
                                        {{--                  </div>--}}
                                        {{--                  </div>--}}
                                        {{--                        <div class="col-md-6">--}}
                                        {{--                            <div class="form-group">--}}
                                        {{--                                <label for="exampleInputEmail1">Stock</label>--}}
                                        {{--                                <input type="text" name="stock" class="form-control" id="exampleInputEmail1" placeholder="Enter Stock" value="{{$products->stock}}">--}}
                                        {{--                            </div>--}}
                                        {{--                        </div>--}}

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Stock Type</label>
                                                <select class="form-control" name="stock_type" required>
                                                    <option selected="selected"
                                                            value="packet" {{$products->stock_type=='packet'?'selected':''}}>Packet
                                                    </option>
                                                    <option value="quantity" {{$products->stock_type=='quantity'?'selected':'quantity'}}>Quantity
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Stock</label>
                                                <input type="text" name="stock" class="form-control"
                                                       id="exampleInputEmail1" placeholder="Enter Rating"
                                                       value="{{$products->stock}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Is Offer</label>
                                                <select class="form-control" name="is_offer" required>
                                                    <option selected="selected"
                                                            value="1" {{$products->is_offer==1?'selected':''}}>Yes
                                                    </option>
                                                    <option value="0" {{$products->is_offer==0?'selected':''}}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Is Hot Deals</label>
                                                <select class="form-control" name="is_hotdeal" required>
                                                    <option selected="selected"
                                                            value="1" {{$products->is_hotdeal==1?'selected':''}}>Yes
                                                    </option>
                                                    <option value="0" {{$products->is_hotdeal==0?'selected':''}}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Is New Arrival</label>
                                                <select class="form-control" name="is_newarrival" required>
                                                    <option selected="selected"
                                                            value="1" {{$products->is_newarrival==1?'selected':''}}>Yes
                                                    </option>
                                                    <option value="0" {{$products->is_newarrival==0?'selected':''}}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Is Discounted</label>
                                                <select class="form-control" name="is_discounted" required>
                                                    <option selected="selected"
                                                            value="1" {{$products->is_discounted==1?'selected':''}}>Yes
                                                    </option>
                                                    <option value="0" {{$products->is_discounted==0?'selected':''}}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Is Active</label>
                                                <select class="form-control" name="isactive" required>
                                                    <option selected="selected"
                                                            value="1" {{$products->isactive==1?'selected':''}}>Yes
                                                    </option>
                                                    <option value="0" {{$products->isactive==0?'selected':''}}>No
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputtitle">Category</label>
                                                <select name="category_id[]" class="form-control select2"
                                                        id="exampleInputistop" data-placeholder="Select a Category"
                                                        multiple>
                                                    <option value="">Please Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option
                                                            value="{{$category->id}}" @foreach($products->category as $s) @if($s->id==$category->id){{'selected'}}@endif @endforeach >{{$category->name}}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputtitle">Sub Category</label>
                                                <select class="form-control select2" multiple
                                                        data-placeholder="Select a subcategory" style="width: 100%;"
                                                        name="sub_cat_id[]">

                                                    <option value="">Please Select Category</option>
                                                    @foreach($subcategories as $subcategory)

                                                        <option
                                                            value="{{$subcategory->id}}" @foreach($products->subcategory as $s) @if($s->id==$subcategory->id){{'selected'}}@endif @endforeach >{{$subcategory->name}}</option>


                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputimage">Product Image</label>
                                            <input type="file" name="image" class="form-control" id="exampleInputimage"
                                                   placeholder="" multiple>

                                        </div>

                                    </div>
                                </div>

                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <!--*******************************************************************************************************************-->
        <section class="content">
            <div class="container-fluid">
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Add Document Images</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add Images</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data"
                                  action="{{route('product.document',['id'=>$products->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputtitle">Select Size</label>
                                        <select name="size_id" class="form-control" id="exampleInputistop"
                                                placeholder="">


                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputimage">Product Image</label>
                                        <input type="file" name="image[]" class="form-control" id="exampleInputimage"
                                               placeholder="" multiple>

                                    </div>

                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    <br>

                                    <div class="row">


                                    <!-- /.form-group -->
                                        <!-- /.form-group -->
                                        <!-- /.col -->
                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->
                </div>

            </div>
        </section>
        {{--      <***********************************************************************************--}}


        <!-- /.content -->


        {{--      <script src="{{asset('../admin-theme/plugins/jquery/jquery.min.js')}}"></script>--}}


        {{--      ****************************************************************************************--}}

    </div>
    <!-- ./wrapper -->
@endsection
@section('scripts')
    <script src="{{asset('admin-theme/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('admin-theme/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script>
        $(function () {
            // Summernote
            $('.textarea').summernote()
        })
    </script>
{{--    <script type="text/javascript">--}}
{{--        $(document).ready(function () {--}}
{{--            $('select[name="size_id"]').on('change', function () {--}}
{{--                var catID = $(this).val();--}}
{{--                var data = 'size_id=' + catID;--}}
{{--                $.ajax({--}}
{{--                    url: "{{route('product.size.images')}}",--}}
{{--                    type: "GET",--}}
{{--                    dataType: "json",--}}
{{--                    data: data,--}}
{{--                    success: function (data) {--}}

{{--                        $.each(data, function(key, value) {--}}
{{--                            $('select[name="image"]').append('<option value="'+ key +'">'+ value +'</option>');--}}
{{--                        });--}}
{{--                    }--}}

{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--    </script>--}}

    <script>
        $(document).ready(function () {
            $('.select2').select2();
            $('#category_id_sel').select2();
        });
    </script>

    <script>
        function edit_row(no) {
            document.getElementById("edit_button" + no).style.display = "none";
            document.getElementById("save_button" + no).style.display = "block";

            var size = document.getElementById("size" + no);
            var price = document.getElementById("price" + no);
            var cut_price = document.getElementById("cut_price" + no);
            var min = document.getElementById("min_qty" + no);
            var max = document.getElementById("max_qty" + no);
            var image = document.getElementById("image" + no);
            var stock = document.getElementById("stock" + no);
            var consumed_units = document.getElementById("consumed_units" + no);
            var isactive = document.getElementById("isactive" + no);

            var size_data = size.innerHTML;
            var price_data = price.innerHTML;
            var cut_price_data = cut_price.innerHTML;
            var min_data = min.innerHTML;
            var max_data = max.innerHTML;
            var image_data = image.src;
            var stock_data = stock.innerHTML;
            var consumed_units_data = consumed_units.innerHTML;
            var isactive_data1 = isactive.innerHTML
            if (isactive_data1.trim() === "Yes") {
                var isactive_data = '1';
            } else {
                var isactive_data = '0';
            }


            size.innerHTML = "<input type='text' style='width:70px;' id='size_text" + no + "' value='" + size_data + "'>";
            price.innerHTML = "<input type='text' style='width:70px;' id='price_text" + no + "' value='" + price_data + "'>";
            cut_price.innerHTML = "<input type='text' style='width:70px;' id='cut_price_text" + no + "' value='" + cut_price_data + "'>";
            min.innerHTML = "<input type='text' style='width:70px;'  id='min_text" + no + "' value='" + min_data + "'>";
            max.innerHTML = "<input type='text' style='width:70px;' id='max_text" + no + "' value='" + max_data + "'>";
            stock.innerHTML = "<input type='text' style='width:70px;' id='stock_text" + no + "' value='" + stock_data + "'>";
            consumed_units.innerHTML = "<input type='text' style='width:70px;' id='consumed_units_text" + no + "' value='" + consumed_units_data + "'>";
            isactive.innerHTML = "<input type='text' style='width:70px;' id='isactive_text" + no + "' value='" + isactive_data + "'>";
        }

        function save_row(no) {


            var size_val = document.getElementById("size_text" + no).value;
            var price_val = document.getElementById("price_text" + no).value;
            var cut_price_val = document.getElementById("cut_price_text" + no).value;
            var min_val = document.getElementById("min_text" + no).value;
            var max_val = document.getElementById("max_text" + no).value;
            var stock_val = document.getElementById("stock_text" + no).value;
            var consumed_units_val = document.getElementById("consumed_units_text" + no).value;
            var isactive_val = document.getElementById("isactive_text" + no).value;
            // var data = 'price=' + price_val + '&cut_price=' + cut_price_val + '&min_qty=' + min_val + '&c=' + max_val + '&stock=' + stock_val + '&isactive=' + isactive_val + '&size_id=' + no;
            formdata = new FormData();
            formdata.append('size', size_val)
            formdata.append('price', price_val)
            formdata.append('cut_price', cut_price_val)
            formdata.append('min_qty', min_val)
            formdata.append('max_qty', max_val)
            formdata.append('stock', stock_val)
            formdata.append('consumed_units', consumed_units_val)
            formdata.append('isactive', isactive_val)
            formdata.append('size_id', no)
            var files = $('#sel_image'+no)[0].files[0];
            if(files!=undefined)
                formdata.append('file',files);

            $.ajax({
                url: "{{route('product.size.update')}}",
                type: "POST",
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                   // alert(data)
                    window.location.reload();
                    $('#message').html("<h2>Current balance has been updated!</h2>")
                }

            });
        }
    </script>
@endsection
