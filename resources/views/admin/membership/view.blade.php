@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Membership</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Membership</li>
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
                                <a href="{{route('membership.create')}}" class="btn btn-primary">Add Membership</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Cut Price</th>
                                        <th>Profile Limit</th>
                                        <th>Isactive</th>
                                        <th>Months</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($memberships as $membership)
                                        <tr>
                                            <td>{{$membership->title}}</td>
                                            <td>{{$membership->description}}</td>
                                            <td>{{$membership->price}}</td>
                                            <td>{{$membership->cut_price}}</td>
                                            <td>{{$membership->profile_limit}}</td>
                                            <td>
                                                @if($membership->isactive==1){{'Active'}}
                                                @else{{'Inactive'}}
                                                @endif
                                            </td>
                                            <td>{{$membership->months}}</td>
                                            <td><a href="{{route('membership.edit',['id'=>$membership->id])}}" class="btn btn-success">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$memberships->appends(request()->query())->links()}}
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

