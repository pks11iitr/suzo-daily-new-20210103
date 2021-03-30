@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Wallet History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"></li>
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
                                    <div class="col-6">
                                        <h3>Wallet Balance: {{\App\Models\Wallet::balance($user_id)}}</h3>
                                    </div>
                                    <div class="col-6">
                                        <h3>Wallet Cashback:{{\App\Models\Wallet::points($user_id)}}</h3>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="col-12 col-sm-12">
                                    <div class="card card-primary card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Balance History</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Cashback History</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                                    <table id="example2" class="table table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Type</th>
                                                            <th>Details</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
@foreach($balance_history as $history)
    <tr>
        <td>{{$history->type}}</td>
        <td>{{$history->description}}</td>
        <td>{{$history->amount}}</td>
        <td>{{date('d/m/Y h:iA', strtotime($history->created_at))}}</td>

    </tr>
@endforeach                                                  </tbody>
                                                    </table>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                                    <table id="example2" class="table table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Type</th>
                                                            <th>Details</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($cashback_history as $history)
                                                            <tr>
                                                                <td>{{$history->type}}</td>
                                                                <td>{{$history->description}}</td>
                                                                <td>{{$history->amount}}</td>
                                                                <td>{{date('d/m/Y h:iA', strtotime($history->created_at))}}</td>

                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
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
