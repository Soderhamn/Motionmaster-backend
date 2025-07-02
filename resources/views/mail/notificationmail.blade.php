<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $notificationTitle }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000261;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            font-size: 20px;
            color: #333333;
            margin-bottom: 20px;
        }
        p {
            font-size: 14px;
            color: #555555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>{{ $notificationTitle }}</h1>
        <p>Hej {{ $name }},</p>
        <p>{{ $notificationMessage }}</p>
        <p>Öppna Motionmaster-appen för att se mer information om denna notifikation.</p>
        <p style="font-size: 10px;">Vill du inte längre ta emot notifikationer via e-post?<br>Du kan ändra dina inställningar i appen eller klicka på länken nedan för att avregistrera dig från e-postnotifikationer:
        <br><a href="{{ $unsubscribeLink }}">Avregistrera e-postnotifikationer</a></p>
    </div>
</body>
</html>