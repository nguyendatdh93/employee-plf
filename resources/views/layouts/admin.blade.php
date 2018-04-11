<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    @include('layouts.partials.header')
</head>
<body class="skin-blue">
<div class="wrapper">

    <!-- Main Header -->
@include('layouts.partials.main_header')
<!-- Left side column. contains the logo and sidebar -->
@include('layouts.partials.main_sidebar')

<!-- Content Wrapper. Contains page content -->
@include('layouts.partials.content_wrapper')

<!-- Main Footer -->
    @include('layouts.partials.main_footer')

</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
@include('layouts.partials.script')
@section('Datatable') @show
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>