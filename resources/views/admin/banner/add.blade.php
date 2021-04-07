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
                      <h3 class="card-title">Banner Add</h3>
                  </div>
                  <!-- /.card-header -->

                  <form role="form" method="post" enctype="multipart/form-data" action="{{route('banners.store')}}">
                      @csrf

                      <div class="card-body">
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Banner Type</label>
                                  <select class="form-control select2" name="type">

                                      <option value="1">Login Page</option>
                                      <option value="2">Home First</option>
                                      <option value="3">Home Second</option>
                                      <option value="4">Other</option>

                                  </select>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Entity Type</label>
                                  <select class="form-control select2" name="entity_type">
                                      <option value="">Select entity for click action</option>
                                      @foreach($categorys as $category)
                                      <option value="cat_{{$category->id}}">{{$category->name}}(Category)</option>
                                      @endforeach

                                      @foreach($subcategorys as $subcategory)
                                              <option value="subcat_{{$subcategory->id}}">{{$subcategory->name}}(Subcategory)</option>
                                          @endforeach

                                          @foreach($offercategorys as $offercategory)
                                              <option value="offer_{{$offercategory->id}}">{{$offercategory->name}}(Special Category)</option>
                                          @endforeach
                                      @foreach($offers as $offer)
                                          <option value="detailedoffer_{{$offer->id}}">{{$offer->name}}(Offer Detail)</option>
                                      @endforeach

                                  </select>
                              </div>
                              <!-- /.form-group -->
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Image</label>
                                  <div class="input-group">
                                      <div class="custom-file">
                                          <input type="file" name="image" class="custom-file-input" id="exampleInputFile" accept="image/*" required>
                                          <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                      </div>
                                      <div class="input-group-append">
                                          <span class="input-group-text" id="">Upload</span>
                                      </div>
                                  </div>
                              </div>
                              <!-- /.form-group -->
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Isactive</label>
                                  <select class="form-control select2" name="isactive">
                                      <option value="">Please Select Status</option>
                                      <option value="1">Yes</option>
                                      <option value="0">No</option>
                                  </select>
                              </div>
                              <!-- /.form-group -->
                          </div>
                          <!-- /.col -->
                      </div>
                      <!-- /.row -->
                  </div>
                      <div class="card-footer">
                          <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                  <!-- /.card-body -->
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

