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
                                <h3 class="card-title">Edit Super Grocery</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('homesection.productupdate',['id'=>$homesection->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <input type="hidden"  name="type" value="supergrocery">
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="exampleInputEmail1">Title</label>--}}
{{--                                                <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="Enter title" value="{{$homesection->title}}">--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Product Section Type</label>
                                                <select class="form-control" name="type" >
                                                    <option value="product1" @if($homesection->type=='products1'){{'selected'}}@endif>Products 1</option>
                                                    <option value="product2" @if($homesection->type=='products1'){{'selected'}}@endif>Products 2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Name</label>
                                                <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" value="{{$homesection->name}}">
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
{{--                                        <div class="col-md-6">--}}
{{--                                            <div class="form-group">--}}
{{--                                                <label for="exampleInputFile">File input</label>--}}


{{--                                                <input type="file" name="image" class="form-control" id="exampleInputFile" accept="image/*" >--}}

{{--                                            </div>--}}
{{--                                            <img src="{{$homesection->image}}" height="100" width="200">--}}
{{--                                        </div>--}}


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

        {{--***************************************************************************************--}}

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
                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('homesection.productimage',['id'=>$homesection->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Product Name</label>
                                        <select class="form-control" name="entity_type" id="entity_type">
                                            <option value="">Please Select....</option>
                                            @foreach($products as $product)
                                                <option value="prod_{{$product->id}}">
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
{{--                                        <th>Sequence No</th>--}}
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($homesectionentity as $hs)
                                        <tr>
                                            <td>{{$hs->entity->name??''}}</td>
{{--                                            <td>{{$hs->sequence_no??''}}</td>--}}
                                            <td>
                                                <a href="{{route('homesection.productdelete',['id'=>$hs->id])}}" class="btn btn-danger">Delete</a>
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
@section('scripts')
    <script>

        $(document).ready(function(){
            $("#entity_type").select2()
        })

    </script>
@endsection

