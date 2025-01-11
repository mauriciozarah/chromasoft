<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login de Usuário</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/datatables.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/swal.css')}}" />
</head>
<body>
    <div class="wrapper" style="width:100% !important;height:960px !important">
        @yield('content')
    </div>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.js')}}"></script>
    <script src="{{asset('assets/js/datatablesbr.js')}}"></script>
    <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/swal.js')}}"></script>
    
    @yield('script')
</body>
</html>