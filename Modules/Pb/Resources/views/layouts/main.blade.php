<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Sancoin</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700&amp;subset=vietnamese">
	<link rel="stylesheet" href="{{url_sync('assets/pb/css/fontawesome.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{url_sync('assets/pb/css/bootstrap.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{url_sync('assets/pb/css/scrollbar.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{url_sync('assets/pb/css/style.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{url_sync('assets/pb/css/custom.css')}}" type="text/css" />
	<link rel="stylesheet" href="{{url_sync('assets/pb/plugin/sweetalert/sweetalert2.css')}}" type="text/css" />
</head>
<body>
	<div class="page-container">

		@include('pb::layouts.main.header')

		<div class="site-content" role="main">
			@yield('content')
		</div>

		@yield('popup-content')

		@include('pb::layouts.main.footer')
	</div>
	<script src="{{url_sync('assets/pb/js/jquery.1.12.4.min.js')}}"></script>
	<script src="{{url_sync('assets/pb/js/bootstrap.js')}}"></script>
	<script src="{{url_sync('assets/pb/js/scrollbar.min.js')}}"></script>
	<script src="{{url_sync('assets/pb/js/jquery-loading-overlay/src/loadingoverlay.min.js')}}"></script>
	<script src="{{url_sync('assets/pb/plugin/sweetalert/sweetalert2.js')}}"></script>
	<script src="{{url_sync('assets/pb/js/custom.js')}}"></script>
	<script src="{{url_sync('assets/pb/js/global.js')}}"></script>
	@yield('extend-js')
	</body>
</html>