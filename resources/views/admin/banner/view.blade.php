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
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Banner</li>
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
                      <th>Banner ID</th>
                      <th>Image</th>
                      <th>Type</th>
                      <th>Entity</th>
                      {{--<th>Parent Category</th>--}}
                      <th>Isactive</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
				@foreach($banners as $bann)
                  <tr>
                      <td>{{$bann->id}}</td>
                      <td><img src="{{$bann->image}}" height="80px" width="80px"/></td>
                      <td>{{$bann->type==1?'Login':($bann->type==2?'Home First':($bann->type==3?'Home Second':'Other'))}}</td>
                          <td>
                              @if($bann->entity_type=='App\Models\Category')
                              {{$bann->entity->name??''}}(Category)
                              @elseif($bann->entity_type=='App\Models\SubCategory')
                                  {{$bann->entity->name??''}}(Sub Category)
                              @elseif($bann->entity_type=='App\Models\SpecialCategory')
                                  {{$bann->entity->name??''}}(Special Category)
                              @elseif($bann->entity_type=='App\Models\OfferDetail')
                                  {{$bann->entity->name??''}}(Offer Detail)
                              @endif
                          </td>


                      {{--<td>{{$bann->parent_category}}</td>--}}
                       <td>
                        @if($bann->isactive==1){{'Yes'}}
                             @else{{'No'}}
                             @endif
                        </td>
                      <td><a href="{{route('banners.edit',['id'=>$bann->id])}}" class="btn btn-success">Edit</a>
                      <a href="{{route('banners.delete',['id'=>$bann->id])}}" class="btn btn-danger">Delete</a></td>
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
    <!-- /.content -->

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@endsection

