<!DOCTYPE html>
<html lang="sv">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Motionmaster</title>
        <link rel="stylesheet" href="{{ asset('style.css') }}?v={{ filemtime(public_path('style.css')) }}">
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    </head>
    <body>
        <header>
            <h1>Motion Master</h1>
        </header>
        <main>
            <h2>Support</h2>
            <p>För hjälp och support, använd gärna kontaktformuläret i appen under "Hjälp" i huvudmenyn.</p>
            <p>Du kan också kontakta mig via e-post:</p>
            <p><strong>Jadranka Lanfjord</strong></p>
            <p>E-post: <a href="mailto:lanfjord@telia.com">lanfjord@telia.com</a></p>
            
            </div>
        </main>
        <footer>
            <a href="{{ route('welcome') }}" class="a">Tillbaka till startsidan</a>
        </footer>
    </body>
</html>
