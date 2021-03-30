@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('customer.list')}}">Customer</a></li>
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
                <h3 class="card-title">Customer Update</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('customer.update',['id'=>$customers->id])}}">
                 @csrf
                <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" value="{{$customers->name}}">
                </div>

                <div class="form-group">
                        <label>Is Active</label>
                        <select class="form-control" name="status" required>
                           <option  selected="selected" value="1" {{$customers->status==1?'selected':''}}>Active</option>
                            <option value="0" {{$customers->status==0?'selected':''}}>Inactive</option>
                            <option value="2" {{$customers->status==2?'selected':''}}>Blocked</option>
                        </select>
                      </div>
                    <div class="form-group">
                        <label>Membership</label>
                        <select class="form-control" name="active_membership" required>
                            <option value="0">Select Membership</option>
                            @foreach($memberships as $member)
                                <option value="{{$member->id}}" @if($member->id==$customers->active_membership){{'selected'}}@endif>{{$member->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Membership Expiry</label>
                        <input type="date" name="membership_expiry" class="form-control" id="exampleInputEmail1" placeholder="Enter State" value="{{$customers->membership_expity}}">
                    </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile" accept="image/*">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>

                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>

                      </div>

                    </div>
                  </div>
                  <image src="{{$customers->image}}" height="100" width="200">
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

