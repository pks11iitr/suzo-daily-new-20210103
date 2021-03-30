@extends('layouts.admin')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Customers</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item active">Customers</li>
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
        <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">
                     <div class="row">
					      <div class="col-4">
                           <input  class="form-control" name="search" placeholder=" search name" value="{{request('search')}}"  type="text" />
                           </div>
					  <div class="col-4">
                          <select id="ordertype" name="ordertype" class="form-control" >
                             <option value="DESC" {{ request('ordertype')=='DESC'?'selected':''}}>DESC</option>
                              <option value="ASC" {{ request('ordertype')=='ASC'?'selected':''}}>ASC</option>
                          </select>
                      </div>
                      <div class="col-4">
                          <select id="status" name="status" class="form-control" >

                             <option value="">Please Select Status</option>

                             <option value="1" {{ request('status')=='1'?'selected':''}}>Active</option>
                             <option value="0" {{ request('status')==='0'?'selected':''}}>Inactive</option>
                             <option value="2" {{ request('status')=='2'?'selected':''}}>Blocked</option>
                          </select>
                      </div><br><br>
                      <div class="col-4">
                           <input   class="form-control" name="fromdate" placeholder=" search name" value="{{request('fromdate')}}"  type="date" />
                           </div>
                           <div class="col-4">
                           <input  class="form-control" name="todate" placeholder=" search name" value="{{request('todate')}}"  type="date" />
                           </div>
                         <div class="col-4">
                             <select id="status" name="membership" class="form-control" >
                                 <option value="">All Users</option>
                                 <option value="1" {{ request('membership')=='1'?'selected':''}}>Active Membership</option>
                             </select>
                         </div>
                    <div class="col-4">
                       <button type="submit" name="save" class="btn btn-primary">Submit</button>
                        <a href="{{route('customer.list')}}" class="btn btn-danger">Reset Filters</a>
                     </div>
                         <div class="col-4">
                             <a href="{{route('customer.list')}}" type="submit" name="save" class="btn btn-primary">Reset</a>
                         </div>
                         <div class="col-4">
                             <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['type' => 'export'])) }}" class="btn btn-info">Download</a>
                         </div>
                  </div>
              </form>
         </div>

     </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Image</th>
                    <th>Registered On</th>
                    <th>Membership</th>
                    <th>Isactive</th>
                   <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
				@foreach($customers as $customer)
                  <tr>
					  <td>{{$customer->name}}</td>
					  <td>{{$customer->mobile}}</td>
					  <td>{{$customer->email}}</td>
                      <td><img src="{{$customer->image}}" height="80px" width="80px"/></td>
                      <td>{{date('d/m/Y h:ia', strtotime($customer->created_at))}}</td>
                      <td>@if($customer->isMembershipActive()){{$customer->membership->name??'--'}}@endif</td>
                       <td>
                        @if($customer->status==1){{'Active'}}
                             @elseif($customer->status==2){{'Blocked'}}@else{{'Inactive'}}
                             @endif
                        </td>
                      <td><a href="{{route('customer.edit',['id'=>$customer->id])}}" class="btn btn-success">Edit</a>
                          <a href="{{route('user.wallet.history', ['id'=>$customer->id])}}" target="_blank" class='btn btn-primary'>Wallet History</a>&nbsp;&nbsp;&nbsp;
{{--                      <a href="{{route('customer.edit',['id'=>$customer->id])}}" class="open-AddBookDialog btn btn-success" data-toggle="modal" data-target="#exampleModal" data-id="{{$customer->id}}">Notification</a></td>--}}
                          <a href="javascript:void(0)" class='btn btn-primary' onclick="openWalletPanel('{{$order->id??''}}', '{{route('user.wallet.balance', ['id'=>$customer->id])}}', {{$customer->id}})">Add/Revoke Balance</a>
                      </td>
                 </tr>
                 @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>DOB</th>
                   <!-- <th>Address</th>
                    <th>City</th>
                    <th>State</th>-->
                    <th>Image</th>
                    <th>Isactive</th>
                   <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              {{$customers->appends(request()->query())->links()}}
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form role="form" enctype="multipart/form-data" onsubmit=" return verifySubmit()" >
			 @csrf
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Title:</label>
            <input type="text" name="title" class="form-control" id="recipient-name" required>
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" name="message" id="message-text" required></textarea>
          </div>
          <input type="hidden" name="cusid" class="form-control" id="cusid">
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             <button class="btn btn-primary" type="submit">Send message</button>
           </div>
        </form>
      </div>

    </div>
  </div>
</div>

        <div class="modal fade show" id="modal-lg" style="display: none; padding-right: 15px;" aria-modal="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add/Remove Cashback/Wallet Balance&nbsp;&nbsp;&nbsp;&nbsp;Balance:<span id="user-wallet-balance"></span>&nbsp;&nbsp;Cashback:<span id="user-wallet-cashback"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" id="booking-form-section">
                        <form role="form" method="post" enctype="multipart/form-data" action="{{route('wallet.add.remove')}}">
                            @csrf
                            <input type="hidden" name="order_id" id="wallet-order-id" value="1">
                            <input type="hidden" name="user_id" id="wallet-user-id" value="1">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Select Add/Revoke</label>
                                            <select class="form-control" name="action_type" required="">
                                                <option value="">Select Any</option>
                                                <option value="add">Add</option>
                                                <option value="revoke">Revoke</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Calculation Type</label>
                                            <select class="form-control" name="calculation_type" required="">
                                                <option value="">Select Any</option>
                                                <option value="fixed">Fixed Amount</option>
                                                <option value="percentage">Percentage</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Type(Cashback/Wallet Balance)</label>
                                            <select class="form-control" name="amount_type" required="">
                                                <option value="">Select Any</option>
                                                <option value="cashback">Cashback</option>
                                                <option value="balance">Wallet Balance</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Amount</label>
                                            <input type="number" name="amount" class="form-control" required="" value="0.0" min="0.01" step=".01">
                                        </div>

                                    </div>


                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Description</label>
                                    <input type="text" name="wallet_text" class="form-control" required="" placeholder="Max 150 characters">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    {{--                <div class="modal-footer justify-content-between">--}}
                    {{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                    {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                    {{--                </div>--}}
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>



<script>

    function openWalletPanel(id, url, user_id){
        $("#wallet-order-id").val(id)
        $("#wallet-user-id").val(user_id)
        $.ajax({
            url:url,
            method:'get',
            datatype:'json',
            success:function(data){
//alert(data)
                if(data.status=='success'){
//alert()
                    $("#user-wallet-balance").html(data.data.balance)
                    $("#user-wallet-cashback").html(data.data.cashback)

                }

            }
        })
        $("#modal-lg").modal('show')

    }


$(document).on("click", ".open-AddBookDialog", function () {
     var myBookId = $(this).data('id');
     $(".modal-body #cusid").val( myBookId );
     // As pointed out in comments,
     // it is unnecessary to have to manually call the modal.
     // $('#addBookDialog').modal('show');
});
function verifySubmit(){

	var cusid = $("#cusid").val();
	var title = $("#recipient-name").val();
	var des = $("#message-text").val();

	$.post('{{route('customer.send_message')}}', {cusid:cusid, _token:'{{csrf_token()}}', title:title, des:des}, function(data){
					alert('Message has been sent successfully')
			})

             window.location.reload();
           // console.log(data);

	}
 </script>
      <!-- /.container-fluid -->
    </section>
    </div>
    <!-- /.content -->

  <!-- /.control-sidebar -->

<!-- ./wrapper -->
@endsection

