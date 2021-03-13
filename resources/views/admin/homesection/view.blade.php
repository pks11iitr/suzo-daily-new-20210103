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
                            <li class="breadcrumb-item active">Home Section</li>
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
                                <div class="row">
                                    <div class="col-4">
                                        <div class="card card-primary">
                                        <a href="{{route('homesection.bannercreate')}}" class="btn btn-primary">+ Add Banner Section</a>
                                        </div>
                                    </div>
                                <div class="col-4">
                                    <div class="card card-primary">
                                        <a href="{{route('homesection.supergrocerycreate')}}" class="btn btn-primary">+ Add Product Section</a>
                                    </div>
                                </div>
                                    <div class="col-4">
                                        <div class="card card-primary">
                                            <a href="{{route('homesection.subcategorycreate')}}" class="btn btn-primary">+ Add Category Section</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
{{--                                        <th>Name</th>--}}
                                        <th>Sequence No</th>
{{--                                        <th>Image</th>--}}
                                        <th>Type</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($homesections as $homesection)
                                        <tr>
                                            <td>{{$homesection->name??$homesection->entities[0]->entity_id??'--'}}</td>
{{--                                            <td>{{$homesection->name}}</td>--}}
                                            <td>{{$homesection->sequence_no}}</td>
{{--                                            <td><img src="{{$homesection->image}}" height="80px" width="80px"/></td>--}}
                                            <td>{{ucwords($homesection->type)}}</td>
                                            <td>
                                                @if($homesection->isactive==1){{'Yes'}}
                                                @else{{'No'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($homesection->type=='banner')
                                                <a href="{{route('homesection.banneredit',['id'=>$homesection->id])}}" class="btn btn-success">Edit</a>

                                                @elseif($homesection->type=='product2')
                                                <a href="{{route('homesection.productedit',['id'=>$homesection->id])}}" class="btn btn-success">Edit</a>
                                                @elseif($homesection->type=='product1')
                                                    <a href="{{route('homesection.productedit',['id'=>$homesection->id])}}" class="btn btn-success">Edit</a>
                                                @elseif($homesection->type=='subcategory')
                                                <a href="{{route('homesection.subcategoryedit',['id'=>$homesection->id])}}" class="btn btn-success">Edit</a>
                                                @endif
                                                    <a href="{{route('homesection.homesectiondelete',['id'=>$homesection->id])}}" class="btn btn-danger">Delete</a>
                                            </td>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$homesections->appends(request()->query())->links()}}
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

