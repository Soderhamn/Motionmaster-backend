<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Välkommen till Motionmaster</title>
    <style>
        * {
            box-sizing: border-box;
        }
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
        <h1>Välkommen till Motionmaster - bekräfta din e-postadress</h1>
        <p>Hej {{ $name }},</p>
        <p>Välkommen till Motionmaster! Vi är glada att du har valt att bli medlem hos oss.</p>
        <p>För att komma igång behöver du bekräfta din e-postadress. Klicka på länken nedan för att slutföra registreringen:</p>
        <p><a href="{{ $confirmLink }}">Bekräfta e-postadress</a></p>
        <p>Om du inte kan klicka på länken kan du kopiera och klistra in den i din webbläsare.</p>
        <p>Om du inte har registrerat dig hos oss kan du ignorera detta meddelande.</p>
        <p>Med vänliga hälsningar,<br>Motionmaster</p>
    </div>
</body>
</html>