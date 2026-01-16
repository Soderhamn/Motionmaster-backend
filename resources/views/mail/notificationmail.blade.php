<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notificationTitle }}</title>
</head>
<body style="margin:0; padding:0; background-color:#000261;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000261" style="background-color:#000261; width:100%; min-width:100%;">
        <tr>
            <td align="center" style="padding:20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:8px; max-width:600px; width:100%;">
                    <tr>
                        <td style="padding:20px; font-family:Arial, sans-serif;">
                            <h1 style="font-size:20px; color:#333333; margin:0 0 20px 0;">{{ $notificationTitle }}</h1>
                            <p style="font-size:14px; color:#555555; line-height:1.6; margin:0 0 20px 0;">Hej {{ $name }},</p>
                            <p style="font-size:14px; color:#555555; line-height:1.6; margin:0 0 20px 0;">{{ $notificationMessage }}</p>
                            <p style="font-size:14px; color:#555555; line-height:1.6; margin:0 0 20px 0;">Öppna Motion Master-appen för att se mer information om denna notifikation.</p>
                            <p style="font-size:10px; color:#888888; line-height:1.4; margin:0;">
                                Vill du inte längre ta emot notifikationer via e-post?<br>
                                Du kan ändra dina inställningar i appen eller klicka på länken nedan för att avregistrera dig från e-postnotifikationer:<br>
                                <a href="{{ $unsubscribeLink }}" style="color:#007BFF; text-decoration:none;">Avregistrera e-postnotifikationer</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>