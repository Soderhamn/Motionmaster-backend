<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Välkommen till Motionmaster</title>
</head>
<body>
    <h1>Välkommen till Motionmaster - bekräfta din e-postadress</h1>
    <p>Hej {{ $name }},</p>
    <p>Välkommen till Motionmaster! Vi är glada att du har valt att bli medlem hos oss.</p>
    <p>För att komma igång behöver du bekräfta din e-postadress. Klicka på länken nedan för att slutföra registreringen:</p>
    <p><a href="{{ $link }}">Bekräfta e-postadress</a></p>
    <p>Om du inte kan klicka på länken kan du kopiera och klistra in den i din webbläsare.</p>
    <p>Om du inte har registrerat dig hos oss kan du ignorera detta meddelande.</p>
    <p>Med vänliga hälsningar,</p>
    <p>Motionmaster</p>
</body>
</html>