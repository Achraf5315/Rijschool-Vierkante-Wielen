{{--
    Homepagina (gastgebruiker).

    Bevat alle informatie over de rijschool en een paar voorbeeld-lesrijpakketten
    met hun prijzen, zodat de bezoeker weet wat de rijschool te bieden heeft.
--}}
<x-public-layout title="Home - Rijschool Vierkante Wielen">

    {{-- Hero / introductie --}}
    <section class="bg-gradient-to-b from-indigo-50 to-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-20 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-gray-900">
                Rijschool Vierkante Wielen
            </h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Haal je rijbewijs bij een rijschool die je écht verder helpt. Persoonlijke
                begeleiding, ervaren instructeurs en een hoog slagingspercentage.
            </p>
            <a href="{{ route('driving-packages.index') }}" class="btn-primary mt-8 px-6 py-3 text-base">
                Bekijk onze lesrijpakketten
            </a>
        </div>
    </section>

    {{-- Over het bedrijf --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-2xl font-semibold text-gray-900">Over ons</h2>
        <div class="mt-6 grid gap-6 sm:grid-cols-3">
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900">Ervaren instructeurs</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Onze instructeurs hebben jarenlange ervaring en passen de lessen aan op jouw tempo.
                </p>
            </div>
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900">Hoog slagingspercentage</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Door persoonlijke begeleiding sla je sneller je examen in één keer.
                </p>
            </div>
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900">Flexibele pakketten</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Van een startpakket tot een compleet examenpakket — er is altijd een pakket dat bij je past.
                </p>
            </div>
        </div>
    </section>

    {{-- Voorbeeldpakketten met prijzen --}}
    <section class="bg-white border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-900">Voorbeelden van onze pakketten</h2>
                <a href="{{ route('driving-packages.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Bekijk alle pakketten &rarr;
                </a>
            </div>

            @if ($featuredPackages->isEmpty())
                <p class="mt-6 text-gray-600">Er zijn momenteel geen pakketten beschikbaar.</p>
            @else
                <div class="mt-6 grid gap-6 sm:grid-cols-3">
                    @foreach ($featuredPackages as $package)
                        <div class="card p-6 flex flex-col hover:shadow-md transition">
                            <h3 class="font-semibold text-gray-900">{{ $package->Name }}</h3>
                            <p class="mt-2 text-sm text-gray-600 flex-1">{{ $package->Description }}</p>
                            <p class="mt-4 text-sm text-gray-500">{{ $package->LessonCount }} lessen</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                &euro; {{ number_format((float) $package->Price, 2, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-public-layout>
