<!DOCTYPE html>
<html lang="sv">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Motionmaster</title>
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    </head>
    <body>
        <header>
            <h1>Motion Master</h1>
        </header>
        <main>
            <p style="text-align: center;">Ladda ner appen från:</p>
            <div class="store-links">
                <a href="https://apps.apple.com/app/motionmaster/id6747353681">
                    <img src="{{ asset('images/app_store_badge.png') }}" alt="App Store">
                </a>
                <a href="https://play.google.com/store/apps/details?id=com.jadranka.motionmaster">
                    <img src="{{ asset('images/google_play_badge.png') }}" alt="Google Play">
                </a>
            </div>
        </main>
        <footer>
            <a href="{{ route('privacy-policy') }}" class="a">Integritetspolicy</a>
        </footer>
    </body>
</html>
