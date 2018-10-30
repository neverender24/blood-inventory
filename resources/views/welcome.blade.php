<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    </head>
    <body>
        <div id="app"></div>
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ asset('js/off-canvas.js') }}"></script>
        <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('js/misc.js') }}"></script>
    </body>
</html>
