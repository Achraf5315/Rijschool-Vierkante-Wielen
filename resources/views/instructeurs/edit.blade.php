@extends('layouts.app')
 
@section('title', 'Instructeur bewerken')
@section('page-title', 'Instructeur bewerken')
@section('breadcrumb', 'Wijzig de gegevens van deze instructeur')
 
@section('header-action')
    <a href="{{ route('instructeurs.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Terug naar overzicht
    </a>
@endsection
 
@section('content')
 
    <div class="max-w-2xl">
 
        @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-4">
                <p class="text-sm font-semibold text-red-700 mb-2">Error 400 – Ongeldige invoer.</p>
                <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
 
        <div class="card p-6 sm:p-8">
 
            {{-- PUT method spoof --}}
            <form action="{{ route('instructeurs.update', $instructeur->InstructorID) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
 
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Persoonsgegevens</p>
 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
 
                    {{-- First name --}}
                    <div>
                        <label for="voornaam" class="block text-sm font-semibold text-gray-700 mb-1">
                            Voornaam <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="voornaam" name="voornaam"
                            value="{{ old('voornaam', $instructeur->FirstName) }}"
                            class="w-full rounded-lg border {{ $errors->has('voornaam') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('voornaam')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Last name --}}
                    <div>
                        <label for="achternaam" class="block text-sm font-semibold text-gray-700 mb-1">
                            Achternaam <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="achternaam" name="achternaam"
                            value="{{ old('achternaam', $instructeur->LastName) }}"
                            class="w-full rounded-lg border {{ $errors->has('achternaam') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('achternaam')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                </div>
 
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Contactgegevens</p>
 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
 
                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                            E-mailadres <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $instructeur->Email) }}"
                            class="w-full rounded-lg border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Phone --}}
                    <div>
                        <label for="telefoon" class="block text-sm font-semibold text-gray-700 mb-1">
                            Telefoonnummer <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="telefoon" name="telefoon"
                            value="{{ old('telefoon', $instructeur->Phone) }}"
                            class="w-full rounded-lg border {{ $errors->has('telefoon') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('telefoon')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                </div>
 
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Rijbewijs & Status</p>
 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
 
                    {{-- Licence number --}}
                    <div>
                        <label for="rijbewijsnummer" class="block text-sm font-semibold text-gray-700 mb-1">
                            Rijbewijsnummer <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="rijbewijsnummer" name="rijbewijsnummer"
                            value="{{ old('rijbewijsnummer', $instructeur->LicenseNumber) }}"
                            class="w-full rounded-lg border {{ $errors->has('rijbewijsnummer') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        @error('rijbewijsnummer')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Active status --}}
                    <div class="flex items-center gap-3 mt-6">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $instructeur->IsActive) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active" class="text-sm font-semibold text-gray-700">Instructeur is actief</label>
                    </div>
 
                </div>
 
                <p class="text-xs text-gray-400 mb-5">
                    <span class="text-red-500">*</span> Verplichte velden
                </p>
 
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                    <a href="{{ route('instructeurs.index') }}"
                       class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuleren
                    </a>
                    <button type="submit" class="btn-primary px-5 py-2">
                        Wijzigingen opslaan
                    </button>
                </div>
 
            </form>
        </div>
 
    </div>
 
@endsection