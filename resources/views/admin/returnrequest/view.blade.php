@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Return Request</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Return Request</li>
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
{{--                                <h3 class="card-title">Retrun Request</h3>--}}

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>OrderID</th>
                                        <th>Store</th>
                                        <th>Product Name</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
                                        <th>Reason</th>
                                        <th>Total After Return</th>
                                        <th>Date & Time</th>
                                        <th>Approve</th>
                                        <th>Reject</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($returns as $return)
                                        <tr>
                                            <td>{{$return->order->refid??''}}</td>
                                            <td>{{$return->store->name??''}}</td>
                                            <td>{{$return->product->name??''}}</td>
                                            <td>{{$return->size->size??''}}</td>
                                            <td>{{$return->quantity}}</td>

                                            <td>{{$return->return_reason}}</td>
                                            <td>{{$return->cost}}</td>
                                            <td>{{$return->created_at}}</td>
                                            <td>
                                                @if($return->status=='pending')
                                                    <a href="{{route('approve.return.request', ['id'=>$return->id])}}" name='status' class="btn btn-success">Approve</a>
                                                @else
                                                    {{$return->status}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($return->status=='pending')
                                                    <form method="POST" action="{{route('cancel.return.request', ['id'=>$return->id])}}">
                                                        <textarea name="reason"></textarea>
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </form>
                                                @else
                                                    {{$return->remark}}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('order.details',['id'=>$return->order_id])}}" class="btn btn-primary">Details</a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        {{$returns->links()}}
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
    </div>
    <!-- ./wrapper -->
@endsection
