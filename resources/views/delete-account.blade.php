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
            <h2>Radera konto</h2>
            <p>Ett konto i Motion Master raderas i första hand inne i appen. Gå in på "Inställningar" och välj "Radera konto".</p>
            <p>Om du inte har tillgång till appen kan du skicka ett e-postmeddelande <strong>från den mailadress som är kopplad till kontot</strong> till <strong>info@jandrankalanfjord.se</strong> med begäran om att radera ditt konto.</p>
            <p>Prenumerationer hanteras via App Store eller Google Play. Om du har en aktiv prenumeration måste du avsluta den i respektive appbutik innan du raderar ditt konto.</p>
            <p>Observera att radering av konto innebär att all data kopplad till kontot försvinner och inte kan återställas.</p>
            
        </main>
        <footer>
            <a href="{{ route('welcome') }}" class="a">Tillbaka till startsidan</a>
        </footer>
    </body>
</html>
