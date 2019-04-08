<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title','Laravel-shop') - Laravel5.8电商 by:wubin.pro</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- 样式 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    @stack('css')
    <style>
        body {
            font-family: Hiragino Sans GB, "Hiragino Sans GB", Helvetica, "Microsoft YaHei", Arial, sans-serif;
            font-size: 14px;
        }
        /* header */

        .navbar-static-top {
            border-color: #e7e7e7;
            background-color: #fff;
            box-shadow: 0px 1px 11px 2px rgba(42, 42, 42, 0.1);
            border-top: 4px solid #00b5ad;
            border-bottom: 1px solid #e8e8e8;
            margin-bottom: 40px;
            margin-top: 0px;
        }

        /* Sticky footer styles */
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            /* Margin bottom by footer height */
            margin-bottom: 60px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            background-color: #000;

        }
        .footer .container {
            padding-right: 15px;
            padding-left: 15px;
        }
        .footer .container p {
            margin: 19px 0;
            color: #c1c1c1;
        }
        .footer .container p a {
            color: inherit;
        }
    </style>
</head>
<body>
<div id="app" class="{{route_class()}}-page">
    @include('layouts._header')
    <div class="container">
        @yield('content')
    </div>
    @include('layouts._footer')
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
@stack('js')
</body>
</html>
