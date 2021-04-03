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
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover" style="width:600px">
                                    <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Old Values</th>
                                        <th>New Value</th>
                                        {{--<th>Parent Category</th>--}}
                                        <th>IP</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($audits as $audit)
                                        <tr>
                                            <td>{{$audit->auditable_type}}<br>{{$audit->auditable_id}}/{{$audit->event}}</td>
                                            <td>{{$audit->getRawOriginal('old_values')}}</td>
                                            <td>{{$audit->getRawOriginal('new_values')}}</td>
                                            <td>
                                                {{$audit->ip_address}}
                                            </td>


                                            {{--<td>{{$bann->parent_category}}</td>--}}
                                            <td>
                                                {{$audit->created_at}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{$audits->links()}}
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

