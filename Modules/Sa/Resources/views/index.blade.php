@extends('sa::layouts.main')
@section('title')
    {{trans('admin.label.dashboard')}}
@endsection
@section('breadcrumb')
    <section class="content-header">
        <h1>
            {{trans('admin.label.dashboard')}}
        </h1>
        {{--<ol class="breadcrumb">--}}
        {{--<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>--}}
        {{--<li><a href="#">Tables</a></li>--}}
        {{--<li class="active">Data tables</li>--}}
        {{--</ol>--}}
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua">BTC</span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number">{{ number_format(floatval($totalBtc)/100000000, 8 , '.' , ',') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red">ETH</span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number">{{ number_format(floatval($totalEth)/1000000000000000000, 18 , '.' , ',') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green">VND</span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total</span>
                        <span class="info-box-number">{{ number_format(floatval($totalVnd), 0 , '.' , ',') }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Members</span>
                        <span class="info-box-number">{{ $members }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
    </section>
@stop
