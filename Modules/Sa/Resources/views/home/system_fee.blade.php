@extends('sa::layouts.main')
@section('title')
    {{trans('admin.label.system_fee')}}
@endsection
@section('extend-css')
@endsection
@section('breadcrumb')
    <section class="content-header">
        <h1>
            {{trans('admin.label.system_fee')}}
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
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <form id="pg-search-form" method="get" action="">
                            <div class="row">
                                <div class="col-sm-6 form-label">
                                    {{trans('admin.label.search_date')}}
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right input-sm" id="datepicker" value="{{ old('search_date') }}" name="search_date" >
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="text-align: center; margin-top: 20px;">
                                <button type="submit" class="btn btn-success">
                                    {{trans('admin.label.search')}}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12" >
                                <table class="table table-bordered table-striped dataTable" role="grid" >
                                    <thead>
                                        <tr role="row">
                                            <th> </th>
                                            <th >{{trans('admin.label.fee_amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr role="row">
                                            <td >BTC</td>
                                            <td > {{ number_format(floatval($fee_btc), 0 , '.' , ',') }}</td>
                                        </tr>
                                        <tr role="row" class="odd">
                                            <td >ETH</td>
                                            <td >{{ number_format(floatval($fee_eth), 0 , '.' , ',') }}</td>
                                        </tr>
                                        <tr role="row">
                                            <td >Total</td>
                                            <td >{{ number_format(floatval($fee_btc) + floatval($fee_eth), 0 , '.' , ',') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('extend-js')
    <script>
      $(function () {
        //Date picker
        $('#datepicker').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd'
        })
      })
    </script>
@endsection
