<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <i class="fa fa-user-circle fa-3x"></i>
            </div>
            <div class="pull-left info">
                <p>{{Auth::guard('admin')->user()->email}}</p>
                <a><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="{!! menu_active(route('admin.index'), 'admin') !!}">
                <a href="{{route('admin.index')}}">
                    <i class="fa fa-bar-chart"></i> <span>{{trans('admin.label.dashboard')}}</span>
                </a>
            </li>

            <li class="treeview {!! menu_active(null, 'admin', [route('admin.trans_list'), route('admin.system_fee')]) !!}">
                <a href="#">
                    <i class="fa fa-bank"></i>
                    <span>{{trans('admin.label.report')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! menu_active(route('admin.trans_list'), 'admin') !!}"><a href="{{ route('admin.trans_list') }}"><i class="fa fa-circle-o"></i> {{trans('admin.label.transaction_management')}}</a></li>
                    <li class="{!! menu_active(route('admin.system_fee'), 'admin') !!}"><a href="{{ route('admin.system_fee') }}"><i class="fa fa-circle-o"></i> {{trans('admin.label.system_fee')}}</a></li>
                </ul>
            </li>

            <li class="treeview {!! menu_active(null, 'admin', [route('admin.user_list')]) !!}">
                <a href="#">
                    <i class="fa fa-user-o"></i>
                    <span>{{trans('admin.label.user_management')}}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{!! menu_active(route('admin.user_list'), 'admin') !!}"><a href="{{route('admin.user_list')}}"><i class="fa fa-circle-o"></i> {{trans('admin.label.user_verification')}}</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>