<html>
<head>
    <title>{{ $title ?? '' }}</title>
    <style>
        html {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    @include('generate.pdf.partials.header')
    <div>
        @yield('container')
    </div>
</body>

</html>