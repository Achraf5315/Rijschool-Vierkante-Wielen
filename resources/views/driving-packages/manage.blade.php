{{--
    Aparte beheerpagina "Lesrijpakketten beheren" (admin & instructeur).

    Toont alle pakketten met knoppen om toe te voegen, te bewerken en te
    verwijderen. Verwijderen gebeurt via een bevestigingsoverlay (echte UI,
    geen browser-popup).
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Lesrijpakketten beheren</h2>
    </x-slot>

    {{-- Flash-meldingen als echte UI-overlay (succes/fout na opslaan of verwijderen) --}}
    @include('partials.flash')

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">Voeg pakketten toe, bewerk of verwijder ze.</p>
                <a href="{{ route('driving-packages.create') }}" class="btn-primary">+ Pakket toevoegen</a>
            </div>

            <div class="mt-6 card overflow-hidden">
                @if ($packages->isEmpty())
                    <p class="p-6 text-gray-600">Er zijn nog geen lesrijpakketten. Voeg het eerste pakket toe.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium">Naam</th>
                                <th class="px-4 py-3 font-medium">Lessen</th>
                                <th class="px-4 py-3 font-medium">Prijs</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium text-right">Acties</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($packages as $package)
                                <tr x-data="{ confirming: false }">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $package->Name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $package->LessonCount }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        &euro; {{ number_format((float) $package->Price, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($package->IsActive)
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Actief</span>
                                        @else
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">Inactief</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('driving-packages.edit', $package) }}"
                                           class="text-indigo-600 hover:text-indigo-800">Bewerken</a>
                                        <button type="button" @click="confirming = true"
                                                class="ml-3 text-red-600 hover:text-red-800">Verwijderen</button>

                                        {{-- Bevestigingsoverlay voor verwijderen --}}
                                        <div x-show="confirming" x-cloak
                                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
                                             @keydown.escape.window="confirming = false">
                                            <div class="card max-w-sm w-full p-6 text-left" @click.outside="confirming = false">
                                                <h4 class="font-medium text-gray-900">Pakket verwijderen?</h4>
                                                <p class="mt-2 text-sm text-gray-600">
                                                    Weet je zeker dat je "{{ $package->Name }}" wilt verwijderen?
                                                    Dit kan niet ongedaan worden gemaakt.
                                                </p>
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" @click="confirming = false" class="btn-secondary">
                                                        Annuleren
                                                    </button>
                                                    <form method="POST" action="{{ route('driving-packages.destroy', $package) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-danger">Verwijderen</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
