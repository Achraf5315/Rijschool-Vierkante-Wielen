{{--
    Gedeeld formulier voor het toevoegen en bewerken van een lesrijpakket.

    Verwacht:
      - $action : de URL waar het formulier naartoe post
      - $method : 'POST' (toevoegen) of 'PUT' (bewerken)
      - $package : bestaand pakket bij bewerken, of null bij toevoegen

    Bestaande waarden worden hergebruikt via old() (na een validatiefout) of via
    het $package model (bij bewerken).
--}}
@php
    $package = $package ?? null;
@endphp

{{-- Foutmelding-overlay: toont dat verplichte velden ontbreken (echte UI, geen browser-popup) --}}
@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-md p-4">
        <p class="font-medium">Niet alle verplichte velden zijn (correct) ingevuld:</p>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div>
        <label for="Name" class="block text-sm font-medium text-gray-700">Naam <span class="text-red-500">*</span></label>
        <input type="text" name="Name" id="Name"
               value="{{ old('Name', $package->Name ?? '') }}"
               class="form-input-field">
    </div>

    <div>
        <label for="Description" class="block text-sm font-medium text-gray-700">Omschrijving <span class="text-red-500">*</span></label>
        <textarea name="Description" id="Description" rows="3"
                  class="form-input-field">{{ old('Description', $package->Description ?? '') }}</textarea>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="LessonCount" class="block text-sm font-medium text-gray-700">Aantal lessen <span class="text-red-500">*</span></label>
            <input type="number" name="LessonCount" id="LessonCount" min="1"
                   value="{{ old('LessonCount', $package->LessonCount ?? '') }}"
                   class="form-input-field">
        </div>

        <div>
            <label for="LessonDuration" class="block text-sm font-medium text-gray-700">Lesduur (minuten) <span class="text-red-500">*</span></label>
            <input type="number" name="LessonDuration" id="LessonDuration" min="15"
                   value="{{ old('LessonDuration', $package->LessonDuration ?? 60) }}"
                   class="form-input-field">
        </div>

        <div>
            <label for="Price" class="block text-sm font-medium text-gray-700">Prijs (&euro;) <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" name="Price" id="Price" min="0"
                   value="{{ old('Price', $package->Price ?? '') }}"
                   class="form-input-field">
        </div>

        <div>
            <label for="Category" class="block text-sm font-medium text-gray-700">Categorie <span class="text-red-500">*</span></label>
            <input type="text" name="Category" id="Category"
                   value="{{ old('Category', $package->Category ?? 'B') }}"
                   class="form-input-field">
        </div>
    </div>

    <div>
        <label for="Notes" class="block text-sm font-medium text-gray-700">Notities (optioneel)</label>
        <textarea name="Notes" id="Notes" rows="2"
                  class="form-input-field">{{ old('Notes', $package->Notes ?? '') }}</textarea>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="IsActive" id="IsActive" value="1"
               {{ old('IsActive', $package->IsActive ?? true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="IsActive" class="text-sm text-gray-700">Pakket is actief (zichtbaar voor bezoekers)</label>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="btn-primary">Opslaan</button>
        <a href="{{ route('driving-packages.manage') }}" class="text-sm text-gray-500 hover:text-gray-800">Annuleren</a>
    </div>
</form>
