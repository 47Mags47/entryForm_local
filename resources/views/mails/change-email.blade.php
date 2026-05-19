<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Подтверждение смены email в services</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 100vw;
            height: 100vh;
            background-color: #f0f2f5;
        }

        .mail-layout {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #f8fafd;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 20px 10px #eee;
            font-family: sans-serif;
            color: #333333;
            line-height: 1.6;
        }

        .logo {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: rgb(34, 34, 34);
            margin-bottom: 20px;
        }

        .text {
            text-align: center;
            font-size: 18px;
            color: rgb(34, 34, 34);
            margin-bottom: 10px;
        }

        .highlight {
            font-weight: bold;
            color: #0e2938;
            background-color: #e6f0ff;
            padding: 4px 8px;
            border-radius: 4px;
            display: inline-block;
            margin: 8px 0;
        }

        .accept-button-wrapper {
            text-align: center;
            margin: 20px 0 30px;
            padding: 10px 0;
        }

        .accept-button {
            background-color: #83adf0;
            width: 300px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            padding: 12px;
            border-radius: 6px;
            display: inline-block;
            border: none;
            color: white !important;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .accept-button:hover {
            background-color: #88a2ff;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
            text-align: center;
            line-height: 1.4;
        }

        .note {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-top: 15px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="mail-layout">
        <div class="email-container">
            <div class="logo">
                {{ config('app.name') }}
            </div>

            <div class="text">
                Здравствуйте.
            </div>

            <div class="text">
                Вы запросили смену email-адреса
            </div>

            <div class="text">
                Пожалуйста, подтвердите это изменение, нажав кнопку ниже.
            </div>

            <div class="accept-button-wrapper">
                <a href="{{ route('change-email.accept', ['token' => $model->token]) }}"
                   class="accept-button">
                    Подтвердить смену email
                </a>
            </div>

            <div class="note">
                Если вы не запрашивали смену email, просто проигнорируйте это письмо.<br>
                Ваш старый email останется активным.
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.<br>
            </div>
        </div>
    </div>
</body>

</html>
