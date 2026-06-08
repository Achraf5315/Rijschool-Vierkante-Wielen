<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Rijles Overzicht
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Bekijk alle actieve rijlessen van Vierkante Wielen.
                </p>
            </div>

            <a href="{{ route('driving-lessons.create') }}"
               class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                Rijles Toevoegen
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                @if (session('success'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                @if (count($lessons) > 0)
                    @php
                        $statusLabels = [
                            'Open' => 'Open',
                            'Planned' => 'Gepland',
                            'Confirmed' => 'Bevestigd',
                            'InProgress' => 'Bezig',
                            'Completed' => 'Afgerond',
                            'CancelledByClient' => 'Geannuleerd door leerling',
                            'CancelledByInstructor' => 'Geannuleerd door instructeur',
                        ];

                        $statusClasses = [
                            'Open' => 'bg-slate-100 text-slate-700',
                            'Planned' => 'bg-blue-100 text-blue-700',
                            'Confirmed' => 'bg-emerald-100 text-emerald-700',
                            'InProgress' => 'bg-amber-100 text-amber-700',
                            'Completed' => 'bg-emerald-100 text-emerald-700',
                            'CancelledByClient' => 'bg-rose-100 text-rose-700',
                            'CancelledByInstructor' => 'bg-rose-100 text-rose-700',
                        ];
                    @endphp

                    <div class="mb-4 overflow-hidden rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                <tr>
                                    <th class="px-4 py-3">Leerling</th>
                                    <th class="px-4 py-3">Instructeur</th>
                                    <th class="px-4 py-3">Lesvoertuig</th>
                                    <th class="px-4 py-3">Datum/Tijd</th>
                                    <th class="px-4 py-3">Locatie</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-center">Wijzig</th>
                                    <th class="px-4 py-3 text-center">Verwijder</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white text-sm text-gray-700">
                                @foreach ($lessons as $lesson)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 font-medium text-gray-900">
                                            {{ $lesson['ClientFirstName'] . ' ' . $lesson['ClientLastName'] }}
                                        </td>
                                        <td class="px-4 py-4">
                                            {{ $lesson['InstructorFirstName'] . ' ' . $lesson['InstructorLastName'] }}
                                        </td>
                                        <td class="px-4 py-4">
                                            {{ $lesson['VehicleBrand'] . ' ' . $lesson['VehicleModel'] . ' (' . $lesson['VehicleLicensePlate'] . ')' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            {{ \Illuminate\Support\Carbon::parse($lesson['StartTime'])->format('d-m-Y H:i') }}
                                            tot
                                            {{ \Illuminate\Support\Carbon::parse($lesson['EndTime'])->format('H:i') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            {{ $lesson['Location'] ?: '-' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$lesson['Status']] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $statusLabels[$lesson['Status']] ?? $lesson['Status'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <a href="{{ route('driving-lessons.edit', $lesson['Id']) }}"
                                               class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-700 transition hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2"
                                               aria-label="Wijzig rijles">
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 1 1 2.828 2.828l-8.9 8.9a2 2 0 0 1-.878.514l-3.25.878.878-3.25a2 2 0 0 1 .513-.878l8.809-8.992Zm1.415-1.414a4 4 0 0 0-5.657 0L1.83 9.692a4 4 0 0 0-1.027 1.755l-1.14 4.21a1 1 0 0 0 1.225 1.225l4.21-1.14a4 4 0 0 0 1.755-1.027l8.148-8.148a4 4 0 0 0 0-5.657Z" />
                                                </svg>
                                            </a>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <a href="{{ route('driving-lessons.destroy', $lesson['Id']) }}"
                                               class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-2"
                                               aria-label="Verwijder rijles">
                                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.5 2a1 1 0 0 0-.894.553L7.382 3H4a1 1 0 0 0 0 2h12a1 1 0 0 0 0-2h-3.382l-.224-.447A1 1 0 0 0 11.5 2h-3Zm-3 6a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1Zm5 1a1 1 0 1 0-2 0v6a1 1 0 1 0 2 0V9Zm3-1a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1ZM5 6h10l-.5 9.5A2 2 0 0 1 12.5 17h-5a2 2 0 0 1-1.999-1.5L5 6Z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center text-gray-600">
                        Er zijn momenteel geen rijlessen beschikbaar.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>