<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>{{ $exception->getStatusCode() }} | {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            text-align: center;
            max-width: 48rem;
            margin: 0 auto;
            padding: 1rem;
        }

        .title {
            font-size: 10rem;
            font-weight: 800;
            color: #dc2626;
        }

        .subtitle {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 1rem;
            color: #1f2937;
        }

        .description {
            color: #4b5563;
            margin-top: 0.5rem;
        }

        .button {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            background-color: #83adf0;
            border-radius: 0.5rem;
            color: white;
            text-decoration: none;
            margin-top: 1.5rem;
        }

        .button:hover {
            background-color: #88a2ff;
        }
    </style>
</head>
{{-- @dd($exception) --}}
<body>
    <div class="container">
        <h1 class="title">{{ $exception->getStatusCode() }}</h1>
        <h2 class="subtitle">{{ __($exception->getMessage(), ['route'=> 'test']) }}</h2>
        <p class="description">Попробуйте обновить страницу или вернуться на главную</p>
        <a href="{{ route('home') }}" class="button">
            🏠 На главную
        </a>
    </div>
</body>

</html>
