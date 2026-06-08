{{--
    Openbaar overzicht van lesrijpakketten (gastgebruiker).

    Toont de beschikbare (actieve) pakketten, of een nette melding als er geen
    pakketten beschikbaar zijn. Het beheren van pakketten gebeurt op het dashboard.
--}}
<x-public-layout title="Lesrijpakketten - Rijschool Vierkante Wielen">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">

        <h1 class="text-2xl font-semibold text-gray-900">Lesrijpakketten</h1>
        <p class="mt-1 text-gray-600">Bekijk onze beschikbare pakketten en wat je ervoor krijgt.</p>

        @if ($packages->isEmpty())
            {{-- Scenario "Geen lesrijpakketten beschikbaar" --}}
            <div class="mt-8 card p-8 text-center text-gray-600">
                Er zijn momenteel geen lesrijpakketten beschikbaar.
            </div>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($packages as $package)
                    <div class="card p-6 flex flex-col hover:shadow-md transition">
                        <h2 class="font-semibold text-gray-900">{{ $package->Name }}</h2>
                        <p class="mt-2 text-sm text-gray-600 flex-1">{{ $package->Description }}</p>

                        <ul class="mt-4 text-sm text-gray-500 space-y-1">
                            <li>{{ $package->LessonCount }} lessen</li>
                            <li>{{ $package->LessonDuration }} minuten per les</li>
                            <li>Categorie {{ $package->Category }}</li>
                        </ul>

                        <p class="mt-4 text-2xl font-bold text-gray-900">
                            &euro; {{ number_format((float) $package->Price, 2, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</x-public-layout>
