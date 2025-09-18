<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr" data-framework="laravel">
<head>
    <title>Dashboard ecommerce - Vuexy - Bootstrap HTML admin template</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" href="{{ asset('dashboard-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dashboard-assets/images/ico/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/plugins/extensions/ext-component-sweet-alerts.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/themes/semi-dark-layout.css') }}">
    @yield('page_css')

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/core/menu/menu-types/horizontal-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/plugins/extensions/ext-component-toastr.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard-assets/css/style.css') }}">
</head>
<body class="horizontal-layout horizontal-menu navbar-floating footer-static @if(isset($blank_page)) blank-page @endif" data-open="hover" data-menu="horizontal-menu" data-col="" data-asset-path="{{ asset('dashboard-assets/') }}">

@if(!isset($blank_page))
    @include('livewire.dashboard.partials.header')
    @include('livewire.dashboard.partials.menu')
@endif

{{ $slot }}

@if(!isset($blank_page))
    @include('livewire.dashboard.partials.footer')
@endif

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<script src="{{ asset('dashboard-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/vendors/js/ui/jquery.sticky.js') }}"></script>
<script src="{{ asset('dashboard-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('dashboard-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('dashboard-assets/js/core/app.js') }}"></script>
@yield('page_scripts')
<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>
</body>
</html>
