<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

    @routes
    <script defer src="{{ mix('/js/vue.js') }}"></script>
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
