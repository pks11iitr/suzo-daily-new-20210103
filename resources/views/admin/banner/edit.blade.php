@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banner</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('banners.list')}}">Banner</a></li>
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
                <h3 class="card-title">Banner Update</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('banners.update',['id'=>$banner->id])}}">
                 @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Banner Type</label>
                                <select class="form-control select2" name="type">

                                    <option value="1" @if($banner->type==1){{'selected'}}@endif>Login Page</option>
                                    <option value="2" @if($banner->type==2){{'selected'}}@endif>Home First</option>
                                    <option value="3" @if($banner->type==3){{'selected'}}@endif>Home Second</option>
                                    <option value="4" @if($banner->type==4){{'selected'}}@endif>Login Page</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Entity Type</label>
                                <select class="form-control select2" name="entity_type">

                                    <option value="">Select entity for click action</option>
                                        @foreach($offercategorys as $offercategory)
                                            <option value="offer_{{$offercategory->id}}"
                                            @if($banner->entity_id==$offercategory->id && $banner->entity_type=='App\Models\SpecialCategory'){{'selected'}}@endif>{{$offercategory->name}}(Special Category)</option>
                                        @endforeach
                                        @foreach($subcategorys as $subcategory)
                                            <option value="subcat_{{$subcategory->id}}"
                                            @if($banner->entity_id==$subcategory->id && $banner->entity_type=='App\Models\Category'){{'selected'}}@endif>{{$subcategory->name}}( Sub Category)</option>
                                        @endforeach
                                        @foreach($categorys as $category)
                                            <option value="cat_{{$category->id}}"
                                            @if($banner->entity_id==$category->id && $banner->entity_type=='App\Models\SubCategory'){{'selected'}}@endif>{{$category->name}}(Category)</option>
                                        @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <img src="{{$banner->image}}" height="100" width="200">
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Isactive</label>
                                <select class="form-control select2" name="isactive">
                                    <option value="">Please Select Status</option>
                                    <option value="1" {{$banner->isactive==1?'selected':''}}>Yes</option>
                                    <option value="0" {{$banner->isactive==0?'selected':''}}>No</option>
                                </select>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
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
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
      {{--***************************************************************************************--}}
      @if($banner->entity_type=='App\Models\SpecialCategory')
      <section class="content">
          <div class="container-fluid">
              <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                      <!-- jquery validation -->
                      <div class="card card-primary">
                          <div class="card-header">
                              <h3 class="card-title">Product Add</h3>
                          </div>
                          <!-- /.card-header -->
                          <!-- form start -->
                          <form role="form" method="post" enctype="multipart/form-data" action="{{route('banners.special.product',['id'=>$banner->id])}}">
                              @csrf
                              <div class="card-body">
                                  <div class="form-group">
                                      <label>Product Name</label>
                                      <select class="form-control" name="product_id">
                                          <option value="">Please Select....</option>
                                          @foreach($products as $product)
                                              <option value="{{$product->id}}">
                                                  {{$product->name}}</option>
                                          @endforeach
                                      </select>
                                  </div>
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
      {{--***************************************************************************************--}}
      <section class="content">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-12">
                      <div class="card card-primary">
                          <div class="card-header">
                              <h3 class="card-title">Product List</h3>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                              <table id="example2" class="table table-bordered table-hover">
                                  <thead>
                                  <tr>
                                      <th>Product Name</th>
                                      <th>Action</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  @foreach($special_category as $special)
                                      <tr>
                                          <td>{{$special->product->name??''}}</td>
                                          <td>
                                              <a href="{{route('special.product.delete',['id'=>$special->id])}}" class="btn btn-danger">Delete</a>
                                          </td>
                                      </tr>
                                  @endforeach
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
          </div>
          <!-- /.container-fluid -->
      </section>
      <!-- /.control-sidebar -->
      @endif



  </div>
<!-- ./wrapper -->
@endsection

