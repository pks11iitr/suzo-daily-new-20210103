@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Complaint Messages</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item active"><a href="{{route('complain.list')}}">Complaint</a></li>
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
             <div class="card direct-chat direct-chat-primary">
              <div class="card-header">
                <h3 class="card-title">Reply To Complaint</h3>

                <div class="card-tools">
{{--                  <span data-toggle="tooltip" title="3 New Messages" class="badge badge-primary">3</span>--}}
                    @if(!$complaint->is_closed)
                  <a type="button" class="btn btn-primary" href="{{route('complain.close', ['id'=>$complaint->id])}}">
                    Mark As Closed
                  </a>
                    @endif
{{--                  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"--}}
{{--                          data-widget="chat-pane-toggle">--}}
{{--                    <i class="fas fa-comments"></i>--}}
{{--                  </button>--}}
{{--                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>--}}
{{--                  </button>--}}
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  @foreach($complaints as $compalin)
                  @if($compalin->type=='user')
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">user</span>
                      <span class="direct-chat-timestamp float-right">{{$compalin->created_at}}</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                  <!--  <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">-->
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      {{$compalin->description}}
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  @endif
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
                  @if($compalin->type=='admin')
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                     <span class="direct-chat-name float-right">HalloBasket Admin</span>
                      <span class="direct-chat-timestamp float-left">{{$compalin->created_at}}</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <!--<img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">-->
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                      {{$compalin->description}}
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  @endif
                  @endforeach
                  <!-- /.direct-chat-msg -->

                </div>
                <!--/.direct-chat-messages-->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <form  role="form" onsubmit=" return verifySubmit()" enctype="multipart/form-data" >
                  <div class="input-group">
                    <input type="text" id="message" name="message" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-primary" >Send</button>
                    </span>
                  </div>
                   <input type="hidden" id="compid" name="compid" placeholder="Type Message ..." class="form-control" value="{{$compid}}">
                </form>
              </div>
              <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->
              </div>

              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <script>
        // Here the value is stored in new variable x

function verifySubmit(){

	var compid = $("#compid").val();

	var des = $("#message").val();

	$.post('{{route('complain.message')}}', {compid:compid, _token:'{{csrf_token()}}', des:des}, function(data){
					alert('Message has been sent successfully')
			})

             window.location.reload();
           // console.log(data);

	}
    </script>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@endsection

