<script id="lineTmpl" type="text/x-jsrender">
    <tr>
    <td>
    {%:building_name%}
    </td>
    <td> {%:address%}
    </td>
    <td> {%:room_number%}
    </td>
    <td> {%:area%}
    </td>
    </tr>
</script>

<script id="bukkenTabTmpl" type="text/x-jsrender">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-3-{%:count%}-1" data-toggle="tab">{{trans('labels_sa.SA_OM001_021')}}</a></li>
            <li><a href="#tab-3-{%:count%}-2" data-toggle="tab">{{trans('labels_sa.SA_OM001_022')}}</a></li>
        </ul>
        <div class="tab-content">
            <div class="active tab-pane" id="tab-3-{%:count%}-1">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputAmount" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_030')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="inputAmount">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputDate" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_031')}}</label>
                                <div class="input-group date col-sm-4 pd-15">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right col-sm-4 datepicker" id="datepicker">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputDepositMoney" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_032')}}</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputDepositMoney">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputKeyMoney" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_033')}}</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputKeyMoney">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputRenewalFee" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_034')}}</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputRenewalFee">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputDepositFee" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_035')}}</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputDepositFee">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputOtherIncome" class="col-sm-2 control-label">{{trans('labels_sa.SA_OM001_036')}}</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="inputOtherIncome">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab-3-{%:count%}-2">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputCollectingAgency" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_037')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="inputCollectingAgency">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputConstructionCost" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_038')}}</label>
                                <div class="col-sm-8">
                                    <input class="form-control" id="inputConstructionCost">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputCommissionMaterial" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_039')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="inputCommissionMaterial">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputTax" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_040')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="inputTax">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputDepositKeyMoney" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_041')}}</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="inputDepositKeyMoney">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputTenantRefund" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_042')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="inputTenantRefund">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputOtherExpenditure" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_043')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="inputOtherExpenditure">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputComfortManagementFee" class="col-sm-4 control-label">{{trans('labels_sa.SA_OM001_044')}}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="inputComfortManagementFee">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</script>

<script id="errMsgTmpl" type="text/x-jsrender">
    <div class="alert alert-danger alert-dismissable text-left">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        {%props messenger%}
            {%>prop%}<br>
        {%/props%}
    </div>
</script>