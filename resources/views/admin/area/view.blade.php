@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Area List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Area List</li>
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
                                    <div class="col-12">
                                        <a href="{{route('area.create')}}" class="btn btn-primary">Add Area List</a>

                                </div>
                                </div>
                            </div>
                            <!-- /.card-header -->

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>Store</th>
                                        <th>Rider</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($arealists as $arealist)
                                        <tr>
                                            <td>{{$arealist->name}}</td>
                                            <td>{{config('myconfig.cities')[$arealist->city_id]??''}}</td>
                                            <td>{{$arealist->store->name??''}}</td>
                                            <td>{{$arealist->rider->name??''}}</td>
                                            <td>
                                                @if($arealist->isactive==1){{'Yes'}}
                                                @else{{'No'}}
                                                @endif
                                            </td>
                                            <td><a href="{{route('area.edit',['id'=>$arealist->id])}}" class="btn btn-success">Edit</a>
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

