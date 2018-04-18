<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="@if(Session::get('menu') == 'user_management') active @endif"><a href="{{ route('user_management') }}"><span>{{ __('menu.user_management') }}</span></a></li>
            <li class="@if(Session::get('menu') == 'app_setting') active @endif"><a href="{{ route('client_app_setting') }}"><span>{{ __('menu.app_setting') }}</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>