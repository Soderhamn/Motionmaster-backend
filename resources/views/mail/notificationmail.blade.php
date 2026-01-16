<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#000261" style="background-color:#000261;">
  <tr>
    <td align="center" style="padding:20px 10px;">

      <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="width:100%; max-width:600px;">
        <tr>
          <td style="padding:20px; font-family:Arial, sans-serif; color:#333333; font-size:14px; line-height:1.6;">

            <div style="font-size:20px; font-weight:bold; margin-bottom:16px;">
              {{ $notificationTitle }}
            </div>

            Hej {{ $name }},<br><br>

            {{ $notificationMessage }}<br><br>

            Öppna Motion Master-appen för att se mer information om denna notifikation.<br><br>

            <span style="font-size:10px; color:#888888; line-height:1.4;">
              Vill du inte längre ta emot notifikationer via e-post?<br>
              Du kan ändra dina inställningar i appen eller klicka på länken nedan för att avregistrera dig:<br>
              <a href="{{ $unsubscribeLink }}" style="color:#007BFF; text-decoration:none;">
                Avregistrera e-postnotifikationer
              </a>
            </span>

          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>
