@extends('sa::layouts.main')
@section('title')
    {{trans('admin.label.transaction_management')}}
@endsection
@section('extend-css')
@endsection
@section('breadcrumb')
    <section class="content-header">
        <h1>
            {{trans('admin.label.transaction_management')}}
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
                    <div class="box-header">
                        <form id="pg-search-form" method="get" action="">
                            <div class="row">
                                <div class="col-sm-3 form-label">
                                    {{trans('messages.label.username')}}
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control input-sm" value="{{ old('username') }}" name="username">
                                </div>
                                <div class="col-sm-3 form-label">
                                    {{trans('admin.label.status')}}
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control input-sm" name="status">
                                        <option value="">All</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 form-label">
                                    {{trans('admin.label.from_date')}}
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control input-sm datepicker" value="{{ old('from_date') }}" name="from_date">
                                </div>
                                <div class="col-sm-3 form-label">
                                    {{trans('admin.label.to_date')}}
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control input-sm datepicker" value="{{ old('to_date') }}" name="to_date">
                                </div>
                            </div>
                            <hr>
                            <div class="row" style="text-align: center; margin-top: 20px;">
                                <input id="pg-input-page" type="hidden" name="page">
                                <button type="submit" class="btn btn-success">
                                    {{trans('admin.label.search')}}
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="userList_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12 pull-right">
                                    <button type="button" data-toggle="modal" data-target="#pg-modal-approve" class="btn btn-sm btn-flat btn-primary pull-right pg-btn-action" disabled id="pg-btn-delete">{{trans('admin.label.buttonApprove')}}</button>
                                    {{--<button type="button" data-toggle="modal" data-target="#pg-modal-disapprove" class="btn btn-sm btn-flat btn-danger pull-right pg-btn-action" disabled id="pg-btn-verify">{{trans('admin.label.buttonDisapprove')}}</button>--}}
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-12" id="pg-table-area">
                                    @include('sa::home.trans_list_datatable')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pagination-text" id="pg-pagination-text">
                                        {!! $paginationText !!}
                                    </div>
                                    <div id="pg-pagination-html">
                                        {!! $paginationHtml !!}
                                    </div>
                                </div>
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
        @include('sa::home.trans_list_modal')
        @include('sa::common.error_popup', ['id' => 'pg-modal-error', 'message' => trans('admin.message.delete_fail')])
    </section>
@endsection
@section('extend-js')
    <script>
      $(function () {
        //Date picker
        $('.datepicker').datepicker({
          autoclose: true,
          format: 'yyyy-mm-dd'
        })
      })
    </script>
    <script src="{{url_sync('assets/sa/js/list_common.js')}}"></script>
    <script src="{{url_sync('assets/sa/js/transaction/list.js')}}"></script>
@endsection
