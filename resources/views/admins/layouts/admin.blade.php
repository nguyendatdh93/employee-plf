<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    @include('admins.partials.header')
</head>
<body class="skin-blue">
<div class="wrapper">

    <!-- Main Header -->
@include('admins.partials.main_header')
<!-- Left side column. contains the logo and sidebar -->
@include('admins.partials.main_sidebar')

<!-- Content Wrapper. Contains page content -->
@include('admins.partials.content_wrapper')

<!-- Main Footer -->
    @include('admins.partials.main_footer')

</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
@include('admins.partials.script')
@section('Datatable') @show
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>