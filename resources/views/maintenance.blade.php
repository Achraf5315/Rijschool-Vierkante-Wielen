{{--
    Onderhoudspagina.

    Wordt getoond in plaats van de homepagina wanneer de website in
    onderhoudsmodus staat (config/rijschool.php -> maintenance = true).
    Dit is een echte, gestileerde melding-overlay i.p.v. een technische foutpagina.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Onderhoud - Rijschool Vierkante Wielen</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white border border-gray-200 rounded-lg shadow-sm p-8 text-center">
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 text-2xl">
                &#9888;
            </div>
            <h1 class="mt-4 text-xl font-semibold text-gray-900">
                Even geduld
            </h1>
            <p class="mt-2 text-gray-600">
                De homepagina is tijdelijk niet beschikbaar vanwege onderhoud aan de website.
                Probeer het over enige tijd opnieuw.
            </p>
        </div>
    </div>
</body>

</html>
