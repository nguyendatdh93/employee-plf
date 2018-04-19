<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('profile') }}" class="logo"><b>Employee</b> Platform</a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        {{--<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">--}}
            {{--<span class="sr-only">Toggle navigation</span>--}}
        {{--</a>--}}
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"> {{ Auth::user()->email }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user_logout') }}" data-toggle="tooltip" title="{{ __('main_header_user.btn_logout') }}"><i class="fa fa-sign-out"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>