@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banners</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">DataTables</li>
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
                <a href="{{route('banners.create')}}" class="btn btn-primary">Add Banner</a>

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Isactive</th>
                   <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
				@foreach($banners as $bann)
                  <tr>
                      <td><img src="{{$bann->image}}" height="80px" width="80px"/></td>
                      <td>{{$bann->type}}</td>
                       <td>
                        @if($bann->isactive==1)
                               <span style="color:green">Yes</span>
                             @else
                               <span style="color:red">No</span>
                             @endif
                        </td>
                      <td><a href="{{route('banners.edit',['id'=>$bann->id])}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;
                      <a href="{{route('banners.delete',['id'=>$bann->id])}}" class="btn btn-success">Delete</a></td>
                 </tr>
                 @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Image</th>
                    <th>Type</th>
                    <th>Isactive</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
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

