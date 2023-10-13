<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Moolre Payment Gateway - By Darko Awuah Jackson</title>
     {{-- <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ app('url')->asset('public/css/bootstrap-rtl.min.css') }}" /> --}}
    <link rel="stylesheet" type="text/css" href="{{ app('url')->asset('public/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{  app('url')->asset('public/css/login.css')  }}" />
    <link rel="stylesheet" type="text/css" href="{{  app('url')->asset('public/css/trans.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{  app('url')->asset('public/css/dashboard.css') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
</head>
<body>
    @yield('content')
    {{-- <div class="container">
    </div> --}}
</body>
</html>