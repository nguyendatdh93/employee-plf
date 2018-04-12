<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"></li>
            <!-- Optionally, you can add icons to the links -->
            <li class="@if(Session::get('menu') == 'user_profile') active @endif"><a href="{{ route('profile') }}"><span>{{ __('menu.user_profile') }}</span></a></li>
            <li class="@if(Session::get('menu') == 'change_password') active @endif"><a href="{{ route('change_password') }}"><span>{{ __('menu.change_password') }}</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>