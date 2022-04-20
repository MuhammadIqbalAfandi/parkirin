<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="{{ asset('favicon.png') }}"
    />

    @routes
    <script src="{{ mix('/js/vue.js') }}" defer></script>
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
