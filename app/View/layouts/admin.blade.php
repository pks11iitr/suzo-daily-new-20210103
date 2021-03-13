<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Arogya</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin-theme/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('admin-theme/plugins/summernote/summernote-bs4.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Admin Logout Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" class="dropdown-item dropdown-header">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
           <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">-->
            <span class="brand-text font-weight-light">Arogyapeeth CMS</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{\Illuminate\Support\Facades\Storage::url('images/logo.jpeg')}}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Arogyapeeth</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item has-treeview menu-open">
                        <a href="{{route('home')}}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="./index.html" class="nav-link active">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Dashboard v1</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="./index2.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Dashboard v2</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="./index3.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Dashboard v3</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
                    </li>
                    <!--**************************************************************************************************-->
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Setttings

                            </p>
                        </a>
                                                <ul class="nav nav-treeview">
                                                    <li class="nav-item">
                                                        <a href="{{route('banners.list')}}" class="nav-link active">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Banners</p>
                                                        </a>
                                                    </li>
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="{{route('treatment.list')}}" class="nav-link">--}}
{{--                                                            <i class="far fa-circle nav-icon"></i>--}}
{{--                                                            <p>Treatment</p>--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="{{route('painpoint.list')}}" class="nav-link">--}}
{{--                                                            <i class="far fa-circle nav-icon"></i>--}}
{{--                                                            <p>Pain Points</p>--}}
{{--                                                        </a>--}}

{{--                                                    </li>--}}
{{--                                                    <li class="nav-item">--}}
{{--                                                        <a href="{{route('disease.list')}}" class="nav-link">--}}
{{--                                                            <i class="far fa-circle nav-icon"></i>--}}
{{--                                                            <p>Diseases</p>--}}
{{--                                                        </a>--}}

{{--                                                    </li>--}}
                                                    <li class="nav-item">
                                                        <a href="{{route('main-disease.list')}}" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Diseases/Treatments</p>
                                                        </a>

                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{route('video.list')}}" class="nav-link">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            <p>Videos</p>
                                                        </a>

                                                    </li>
                                                </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('clinic.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Clinics Partners

                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('therapists.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Field Therapist
                            </p>
                        </a>
                    </li>
                   <li class="nav-item">
                        <a href="{{route('customer.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Customers

                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('therapy.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Therapies

                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('product.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Products
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('orders.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Orders & Bookings

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                           <li class="nav-item">
                                <a href="{{route('orders.list')}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Therapy Bookings</p>
                               </a>
                           </li>
                            <li class="nav-item">
                                <a href="{{route('sessions.list',['type'=>'therapist-session'])}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Therapy Session</p>
                                </a>
                            </li>
                           <li class="nav-item">
                                <a href="{{route('orders.product')}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Product Purchase</p>
                               </a>
                           </li>
                        </ul>

                    </li>
                    <li class="nav-item">
                        <a href="{{route('orders.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Theapy Sessions

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('sessions.list',['type'=>'clinic-session'])}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Clinic Sessions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('sessions.list',['type'=>'therapist-session'])}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Home Sessions</p>
                                </a>
                            </li>
                        </ul>

                    </li>
                    <li class="nav-item">
                        <a href="{{route('complain.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Complaints & Reviews

                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('complain.list')}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Complaints</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('review.list')}}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reviews</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('news.list')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                News Update

                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('notification.create')}}" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>
                                Send Notification

                            </p>
                        </a>
                    </li>


                    <!--**********************************************************************************************************-->
{{--                    <li class="nav-item">--}}
{{--                        <a href="pages/widgets.html" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-th"></i>--}}
{{--                            <p>--}}
{{--                                Widgets--}}
{{--                                <span class="right badge badge-danger">New</span>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-copy"></i>--}}
{{--                            <p>--}}
{{--                                Layout Options--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                                <span class="badge badge-info right">6</span>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/top-nav.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Top Navigation</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Top Navigation + Sidebar</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/boxed.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Boxed</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/fixed-sidebar.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Fixed Sidebar</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/fixed-topnav.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Fixed Navbar</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/fixed-footer.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Fixed Footer</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/layout/collapsed-sidebar.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Collapsed Sidebar</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-chart-pie"></i>--}}
{{--                            <p>--}}
{{--                                Charts--}}
{{--                                <i class="right fas fa-angle-left"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/charts/chartjs.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>ChartJS</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/charts/flot.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Flot</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/charts/inline.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Inline</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-tree"></i>--}}
{{--                            <p>--}}
{{--                                UI Elements--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/general.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>General</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/icons.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Icons</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/buttons.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Buttons</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/sliders.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Sliders</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/modals.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Modals & Alerts</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/navbar.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Navbar & Tabs</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/timeline.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Timeline</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/UI/ribbons.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Ribbons</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-edit"></i>--}}
{{--                            <p>--}}
{{--                                Forms--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/forms/general.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>General Elements</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/forms/advanced.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Advanced Elements</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/forms/editors.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Editors</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/forms/validation.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Validation</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-table"></i>--}}
{{--                            <p>--}}
{{--                                Tables--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/tables/simple.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Simple Tables</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/tables/data.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>DataTables</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/tables/jsgrid.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>jsGrid</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-header">EXAMPLES</li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="pages/calendar.html" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-calendar-alt"></i>--}}
{{--                            <p>--}}
{{--                                Calendar--}}
{{--                                <span class="badge badge-info right">2</span>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="pages/gallery.html" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-image"></i>--}}
{{--                            <p>--}}
{{--                                Gallery--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-envelope"></i>--}}
{{--                            <p>--}}
{{--                                Mailbox--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/mailbox/mailbox.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Inbox</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/mailbox/compose.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Compose</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/mailbox/read-mail.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Read</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-book"></i>--}}
{{--                            <p>--}}
{{--                                Pages--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/invoice.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Invoice</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/profile.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Profile</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/e-commerce.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>E-commerce</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/projects.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Projects</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/project-add.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Project Add</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/project-edit.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Project Edit</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/project-detail.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Project Detail</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/contacts.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Contacts</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-plus-square"></i>--}}
{{--                            <p>--}}
{{--                                Extras--}}
{{--                                <i class="fas fa-angle-left right"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/login.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Login</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/register.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Register</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/forgot-password.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Forgot Password</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/recover-password.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Recover Password</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/lockscreen.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Lockscreen</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/legacy-user-menu.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Legacy User Menu</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/language-menu.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Language Menu</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/404.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Error 404</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/500.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Error 500</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/pace.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Pace</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="pages/examples/blank.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Blank Page</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="starter.html" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Starter Page</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-header">MISCELLANEOUS</li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="https://adminlte.io/docs/3.0" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-file"></i>--}}
{{--                            <p>Documentation</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-header">MULTI LEVEL EXAMPLE</li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="fas fa-circle nav-icon"></i>--}}
{{--                            <p>Level 1</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item has-treeview">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-circle"></i>--}}
{{--                            <p>--}}
{{--                                Level 1--}}
{{--                                <i class="right fas fa-angle-left"></i>--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                        <ul class="nav nav-treeview">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="#" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Level 2</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item has-treeview">--}}
{{--                                <a href="#" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>--}}
{{--                                        Level 2--}}
{{--                                        <i class="right fas fa-angle-left"></i>--}}
{{--                                    </p>--}}
{{--                                </a>--}}
{{--                                <ul class="nav nav-treeview">--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="#" class="nav-link">--}}
{{--                                            <i class="far fa-dot-circle nav-icon"></i>--}}
{{--                                            <p>Level 3</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="#" class="nav-link">--}}
{{--                                            <i class="far fa-dot-circle nav-icon"></i>--}}
{{--                                            <p>Level 3</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="#" class="nav-link">--}}
{{--                                            <i class="far fa-dot-circle nav-icon"></i>--}}
{{--                                            <p>Level 3</p>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="#" class="nav-link">--}}
{{--                                    <i class="far fa-circle nav-icon"></i>--}}
{{--                                    <p>Level 2</p>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="fas fa-circle nav-icon"></i>--}}
{{--                            <p>Level 1</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-header">LABELS</li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-circle text-danger"></i>--}}
{{--                            <p class="text">Important</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-circle text-warning"></i>--}}
{{--                            <p>Warning</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon far fa-circle text-info"></i>--}}
{{--                            <p>Informational</p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif


        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif


        @if ($message = Session::get('warning'))
            <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif


        @if ($message = Session::get('info'))
            <div class="alert alert-info alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

    <!-- this is for validation errors -->
        @if ($errors->any())
            <?php var_dump($errors); ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Please check the form below for errors
            </div>
        @endif
    </div>
    @yield('content')
    <footer class="main-footer">
        <strong>Copyright &copy; 2019-2020 <a href="http://adminlte.io">Avaskm Technology</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.5
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('admin-theme/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('admin-theme/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('admin-theme/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('admin-theme/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('admin-theme/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('admin-theme/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('admin-theme/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('admin-theme/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('admin-theme/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('admin-theme/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('admin-theme/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('admin-theme/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('admin-theme/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin-theme/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('admin-theme/js/pages/dashboard.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('admin-theme/js/demo.js')}}"></script>

@yield('scripts')

</body>
</html>
