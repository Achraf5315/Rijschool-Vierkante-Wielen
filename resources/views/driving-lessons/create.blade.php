<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Rijles Toevoegen
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Plan een nieuwe rijles in voor een leerling.
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-200 sm:p-8">
                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                <form id="driving-lesson-form" method="POST" action="{{ route('driving-lessons.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="client_id" class="mb-1 block text-sm font-medium text-gray-700">Leerling</label>
                        <select id="client_id" name="client_id" required class="block w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Selecteer een leerling</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client['Id'] }}" @selected(old('client_id') == $client['Id'])>
                                    {{ $client['FullName'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructor_id" class="mb-1 block text-sm font-medium text-gray-700">Instructeur</label>
                        <select id="instructor_id" name="instructor_id" required class="block w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Selecteer een instructeur</option>
                            @foreach ($instructors as $instructor)
                                <option value="{{ $instructor['Id'] }}" @selected(old('instructor_id') == $instructor['Id'])>
                                    {{ $instructor['FullName'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('instructor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vehicle_id" class="mb-1 block text-sm font-medium text-gray-700">Lesauto</label>
                        <select id="vehicle_id" name="vehicle_id" required class="block w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            <option value="">Selecteer een lesauto</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle['Id'] }}" @selected(old('vehicle_id') == $vehicle['Id'])>
                                    {{ $vehicle['FullName'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="start_time" class="mb-1 block text-sm font-medium text-gray-700">Starttijd</label>
                        <input id="start_time" name="start_time" type="datetime-local" value="{{ old('start_time') }}" required class="block w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="mb-1 block text-sm font-medium text-gray-700">Eindtijd</label>
                        <input id="end_time" name="end_time" type="datetime-local" value="{{ old('end_time') }}" required class="block w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        <p id="date-range-error" class="mt-1 text-sm text-red-600"></p>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="mb-1 block text-sm font-medium text-gray-700">Locatie / Opmerkingen</label>
                        <textarea id="location" name="location" rows="4" maxlength="255" required class="block w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('location') }}</textarea>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                            Opslaan
                        </button>
                        <a href="{{ route('driving-lessons.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2">
                            Terug
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('driving-lesson-form');
            const startField = document.getElementById('start_time');
            const endField = document.getElementById('end_time');
            const dateRangeError = document.getElementById('date-range-error');

            function validateDateRange() {
                if (!startField.value || !endField.value) {
                    dateRangeError.textContent = '';
                    return true;
                }

                const startDate = new Date(startField.value);
                const endDate = new Date(endField.value);

                if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) {
                    dateRangeError.textContent = 'Gebruik een geldige datum en tijd.';
                    return false;
                }

                if (startDate >= endDate) {
                    dateRangeError.textContent = 'Eindtijd moet na starttijd liggen.';
                    return false;
                }

                dateRangeError.textContent = '';
                return true;
            }

            startField.addEventListener('change', validateDateRange);
            endField.addEventListener('change', validateDateRange);

            form.addEventListener('submit', function (event) {
                if (!validateDateRange()) {
                    event.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>