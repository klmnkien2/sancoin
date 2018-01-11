<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <i class="fa fa-user-circle fa-3x"></i>
            </div>
            <div class="pull-left info">
                {{--<p>{{Auth::guard('web_sa')->user()->email}}</p>--}}
                <p>kiendv</p>
                <a><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="">
                <a href="{{route('sa.index')}}">
                    <i class="fa fa-bar-chart"></i> <span>{{trans('admin.label.dashboard')}}</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-bank"></i>
                    <span>{{trans('admin.label.report')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> {{trans('admin.label.transaction_management')}}</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> {{trans('admin.label.system_fee')}}</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user-o"></i>
                    <span>{{trans('admin.label.user_management')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> {{trans('admin.label.user_verification')}}</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>