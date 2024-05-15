<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sekawan Rent</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/skin_color.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/custom.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/flatpickr.min.css">

    <style>
        /*-----Preloader---*/
        #loader {
            position: fixed;
            width: 100%;
            height: 100vh;
            z-index: 999999;
            overflow: visible;
            background: #fff url("{{ asset('assets') }}/images/preloaders/1.gif") no-repeat center center;
        }

        .form-control.flatpickr:read-only {
            border-color: #D9D9D9;
            background-color: #ffffff !important;
        }
    </style>

    @yield('css')
</head>
