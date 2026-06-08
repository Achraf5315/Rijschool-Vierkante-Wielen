@extends('layouts.app')
 
@section('title', 'Auto bewerken')
@section('page-title', 'Auto bewerken')
@section('breadcrumb', 'Wijzig de gegevens van dit voertuig')
 
@section('header-action')
    <a href="{{ route('autos.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Terug naar overzicht
    </a>
@endsection
 
@section('content')
 
    <div class="max-w-2xl">
 
        {{-- Validation errors --}}
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
 
            {{-- PUT method spoof for Laravel --}}
            <form action="{{ route('autos.update', $auto->VehicleID) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
 
                    {{-- Licence plate --}}
                    <div class="sm:col-span-2">
                        <label for="kenteken" class="block text-sm font-semibold text-gray-700 mb-1">
                            Kenteken <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="kenteken"
                            name="kenteken"
                            value="{{ old('kenteken', $auto->LicensePlate) }}"
                            class="w-full rounded-lg border {{ $errors->has('kenteken') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                        @error('kenteken')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Brand --}}
                    <div>
                        <label for="merk" class="block text-sm font-semibold text-gray-700 mb-1">
                            Merk <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="merk"
                            name="merk"
                            value="{{ old('merk', $auto->Brand) }}"
                            class="w-full rounded-lg border {{ $errors->has('merk') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                        @error('merk')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Model --}}
                    <div>
                        <label for="model" class="block text-sm font-semibold text-gray-700 mb-1">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="model"
                            name="model"
                            value="{{ old('model', $auto->Model) }}"
                            class="w-full rounded-lg border {{ $errors->has('model') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                        @error('model')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Build year --}}
                    <div>
                        <label for="bouwjaar" class="block text-sm font-semibold text-gray-700 mb-1">
                            Bouwjaar <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="bouwjaar"
                            name="bouwjaar"
                            value="{{ old('bouwjaar', $auto->Year) }}"
                            min="1990"
                            max="{{ date('Y') }}"
                            class="w-full rounded-lg border {{ $errors->has('bouwjaar') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                        @error('bouwjaar')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
 
                    {{-- Availability toggle --}}
                    <div class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            id="is_available"
                            name="is_available"
                            value="1"
                            {{ old('is_available', $auto->IsAvailable) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <label for="is_available" class="text-sm font-semibold text-gray-700">
                            Beschikbaar
                        </label>
                    </div>
 
                </div>
 
                <p class="text-xs text-gray-400 mt-5">
                    <span class="text-red-500">*</span> Verplichte velden
                </p>
 
                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                    <a href="{{ route('autos.index') }}"
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
 