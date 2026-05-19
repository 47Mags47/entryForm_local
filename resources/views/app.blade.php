<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name') }}</title>

    @inertiaHead
    @routes

    @vite('resources/js/app.js')
    @vite('resources/sass/app.sass')
</head>

<body>

    @inertia


</body>

</html>
