<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('admin.label.title_common') }} | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
          href="{{url('assets/sa/admin-lte2/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="{{url('assets/sa/admin-lte2/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{url('assets/sa/admin-lte2/bower_components/Ionicons/css/ionicons.min.css')}}">
    @yield('extend-css')
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('assets/sa/admin-lte2/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{url('assets/sa/admin-lte2/dist/css/skins/_all-skins.min.css')}}">
    <link href="{{url('assets/sa/admin-lte2/plugins/datapicker/bootstrap-datepicker3.min.css')}}" rel="stylesheet">
    <link href="{{url('assets/sa/admin-lte2/plugins/ladda/ladda-themeless.min.css')}}" rel="stylesheet">
    <link href="{{url_sync('assets/sa/css/style.css')}}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    @include('sa::layouts.main.header')
    <!-- Left side column. contains the logo and sidebar -->
        @include('sa::layouts.main.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('breadcrumb')

        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{date('Y')}} <a href="{{route('admin.index')}}">Sancoin Systems Admin</a>.</strong> All rights
        reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{url('assets/sa/admin-lte2/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url('assets/sa/admin-lte2/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{url('assets/sa/admin-lte2/bower_components/fastclick/lib/fastclick.js')}}"></script>
<script src="{{url('assets/sa/admin-lte2/plugins/datapicker/js/bootstrap-datepicker.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('assets/sa/admin-lte2/dist/js/adminlte.min.js')}}"></script>
<script src="{{url('assets/sa/admin-lte2/plugins/jsRender/jsrender.min.js')}}"></script>
<!-- ladda -->
<script src="{{url('assets/sa/admin-lte2/plugins/ladda/spin.min.js')}}"></script>
<script src="{{url('assets/sa/admin-lte2/plugins/ladda/ladda.min.js')}}"></script>
<script src="{{url('assets/sa/admin-lte2/plugins/ladda/ladda.jquery.min.js')}}"></script>
<script src="{{url('assets/sa/admin-lte2/plugins/jsRender/jsrender.min.js')}}"></script>
<script src="{{url_sync('assets/sa/js/app_init.js')}}"></script>
<script src="{{url_sync('assets/sa/js/script.js')}}"></script>
@yield('extend-js')
</body>
</html>
