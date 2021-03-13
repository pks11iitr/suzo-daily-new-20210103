@extends('layouts.admin')
@section('content')
    <link rel="stylesheet" href="{{asset('../admin-theme/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('../admin-theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
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
                <h3 class="card-title">Product Add</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('product.store')}}">
                 @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
                  </div>
                  </div>
                        <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                      <textarea id="description" class="form-control" name="description" placeholder="Description" rows="4" cols="50"></textarea>
                  </div>
                  </div>
                        <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Company</label>
                    <input type="text" name="company" class="form-control" id="exampleInputEmail2" placeholder="Enter Name">
                  </div>
                  </div>
                        <div class="col-md-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Rating</label>
                    <input type="text" name="ratings" class="form-control" min="0" id="exampleInputEmail3" placeholder="Enter rating">
                  </div>
                  </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stock Type</label>
                                <select class="form-control" name="stock_type" required>
                                    <option value="packet">Packet</option>
                                    <option value="quantity">Quantity</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Stock</label>
                                <input type="number" name="stock" class="form-control" min="0" id="exampleInputEmail3" placeholder="Enter rating">
                            </div>
                        </div>
                        <div class="col-md-6">
                  <div class="form-group">
                        <label>Is Offer</label>
                        <select class="form-control" name="is_offer" required>
                           <option value="1">Yes</option>
                           <option value="0">No</option>
                        </select>
                    </div>
                    </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is HotDeals</label>
                                <select class="form-control" name="is_hotdeal" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is New Arrival</label>
                                <select class="form-control" name="is_newarrival" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Discounted</label>
                                <select class="form-control" name="is_discounted" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                   <div class="form-group">
                        <label>Is Active</label>
                        <select class="form-control" name="isactive" required>
                           <option value="1">Yes</option>
                           <option value="0">No</option>
                        </select>
                    </div>
                    </div>
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="exampleInputtitle">Sub Category</label>--}}
{{--                        <select name="category_id[]"  class="form-control select2" id="exampleInputistop" data-placeholder="Select a Category" multiple>--}}
{{--                            <option value="">Please Select Category</option>--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <option value="{{$category->id}}">{{$category->name}}</option>--}}

{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                  <div class="col-md-6">--}}
{{--                      <div class="form-group">--}}
{{--                          <label for="exampleInputtitle">Sub Category</label>--}}
{{--                          --}}{{--                            <select name="sub_cat_id" class="form-control" id="exampleInputistop" placeholder="">--}}
{{--                          <select class="form-control select2" multiple data-placeholder="Select a subcategory" style="width: 100%;" name="sub_cat_id[]">--}}

{{--                              <option value="">Please Select Category</option>--}}
{{--                              @foreach($subcategories as $subcategory)--}}
{{--                                  <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>--}}

{{--                              @endforeach--}}
{{--                          </select>--}}
{{--                      </div>--}}
{{--                  </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                  <div class="form-group">--}}
{{--                    <label for="exampleInputFile">Product Image</label>--}}
{{--                    <div class="input-group">--}}
{{--                      <div class="custom-file">--}}
{{--                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile" accept="image/*" required>--}}
{{--                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>--}}
{{--                      </div>--}}
{{--                      <div class="input-group-append">--}}
{{--                        <span class="input-group-text" id="">Upload</span>--}}
{{--                      </div>--}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                </div>--}}
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name="category_id"]').on('change', function() {
                var catID = $(this).val();
                if(catID) {
                    $.ajax({
                        url: '../subcat/ajax/'+catID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {


                            $('select[name="sub_cat_id"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="sub_cat_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });


                        }
                    });
                }else{
                    $('select[name="sub_cat_id"]').empty();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('#category_id_sel').select2();
        });
    </script>
@endsection

