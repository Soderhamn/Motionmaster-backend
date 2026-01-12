<!DOCTYPE html>
<html lang="sv">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Motion Master</title>
        <link rel="stylesheet" href="{{ asset('style.css') }}?v={{ filemtime(public_path('style.css')) }}">
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
            <p><a href="{{ route('support') }}" class="a">Hjälp och support</a></p>
            <p><a href="{{ route('terms-of-use') }}" class="a">Användarvillkor</a></p>
            <p><a href="{{ route('privacy-policy') }}" class="a">Integritetspolicy</a></p>
            <p><a href="{{ route('delete-account') }}" class="a">Radera konto</a></p>
        </footer>
    </body>
</html>
