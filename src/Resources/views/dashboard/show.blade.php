<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cosmo - {{$error->uid}}</title>
    <link rel="stylesheet" href="{{asset('cosmo.css')}}">
</head>
<body>
    {!! $error !!}
    <script src="{{asset('cosmo.js')}}"></script>
</body>
</html>