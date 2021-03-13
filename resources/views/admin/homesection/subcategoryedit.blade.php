@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Home Section</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('homesection.list')}}">Home Section</a></li>
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
                                <h3 class="card-title">Add SubCategory</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('homesection.subcategoryupdate',['id'=>$homesection->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <input type="hidden"  name="type" value="subcategory">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Name</label>
                                                <input type="text" name="home_section_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" value="{{$homesection->name}}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Sequence No</label>
                                                <input type="number" name="sequence_no" class="form-control" id="exampleInputEmail1" placeholder="Enter Sequence No" min="0" value="{{$homesection->sequence_no}}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Is Active</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="isactive" value="1" {{$homesection->isactive==1?'checked':''}}>
                                                    <label class="form-check-label">Yes</label><br>
                                                    <input class="form-check-input" type="radio" name="isactive" value="0" {{$homesection->isactive==0?'checked':''}}>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
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
        {{--***************************************************************************************--}}

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- jquery validation -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Sub Category Add</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('homesection.subcategoryimage',['id'=>$homesection->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SubCategory</label>
                                            <select class="form-control" name="entity_type" >
                                                <option value="">Please Select....</option>
                                                @foreach($subcategorys as $subcategory)
                                                    <option value="_subcat{{$subcategory->id}}">
                                                        {{$subcategory->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Name</label>
                                                <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Upper Title</label>
                                                <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile" >
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="">Upload</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Sub Category List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Upper Tile</th>
                                        <th>Name</th>
                                        <th>Category Name</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($homesectionentity as $hs)
                                        <tr>
                                            <td>{{$hs->title??''}}</td>
                                            <td>{{$hs->name??''}}</td>
                                            <td>{{$hs->entity->name??''}}</td>
                                            <td><img src="{{$hs->image}}" height="80px" width="80px"/></td>
                                            <td>
                                                <a href="{{route('homesection.subdelete',['id'=>$hs->id])}}" class="btn btn-danger">Delete</a>
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
    </div>
    <!-- ./wrapper -->
@endsection

