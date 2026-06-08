@extends('layouts.app')

@section('title', 'Instructeur Details')
@section('page-title', $instructeur->FirstName . ' ' . $instructeur->LastName)
@section('breadcrumb', 'Overzicht van instructeur details')

@section('header-action')
    <div class="flex items-center gap-3">
        {{-- Solo admin puede editar --}}
        @if(auth()->check() && auth()->user()->rolename === 'admin')
            <a href="{{ route('instructeurs.edit', $instructeur->InstructorID) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Bewerken
            </a>
        @endif
        <a href="{{ route('instructeurs.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Terug
        </a>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Main card --}}
        <div class="md:col-span-2">
            <div class="card p-6 sm:p-8">

                {{-- Header with name --}}
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg font-bold">
                        {{ mb_strtoupper(mb_substr($instructeur->FirstName, 0, 1) . mb_substr($instructeur->LastName, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $instructeur->FirstName }} {{ $instructeur->LastName }}</h1>
                        <p class="text-sm text-gray-600 mt-0.5">Instructeur</p>
                    </div>
                </div>

                {{-- Personal information --}}
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Persoonsgegevens</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Voornaam</span>
                            <span class="text-sm font-medium text-gray-900">{{ $instructeur->FirstName }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Achternaam</span>
                            <span class="text-sm font-medium text-gray-900">{{ $instructeur->LastName }}</span>
                        </div>
                    </div>
                </div>

                {{-- Contact information --}}
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Contactgegevens</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">E-mailadres</span>
                            <a href="mailto:{{ $instructeur->Email }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                {{ $instructeur->Email }}
                            </a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Telefoonnummer</span>
                            <a href="tel:{{ $instructeur->Phone }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                {{ $instructeur->Phone }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- License information --}}
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Rijbewijs & Status</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Rijbewijsnummer</span>
                            <span class="text-sm font-medium text-gray-900 font-mono">{{ $instructeur->LicenseNumber }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <div>
                                @if($instructeur->IsActive)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Actief
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Inactief
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delete action (admin only) --}}
                @if(auth()->check() && auth()->user()->rolename === 'admin')
                    <div class="pt-6 border-t border-gray-200">
                        <form action="{{ route('instructeurs.destroy', $instructeur->InstructorID) }}" method="POST"
                              onsubmit="return confirm('Weet je zeker dat je deze instructeur permanent wilt verwijderen? Deze actie kan niet ongedaan gemaakt worden.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Verwijderen
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        </div>

        {{-- Sidebar --}}
        <div>
            {{-- Vehicle info if linked --}}
            @if($instructeur->LicensePlate)
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Gekoppelde auto</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Kenteken</p>
                            <p class="text-lg font-bold text-yellow-600 font-mono">{{ $instructeur->LicensePlate }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Voertuig</p>
                            <p class="text-sm font-medium text-gray-900">{{ $instructeur->VehicleBrand }} {{ $instructeur->VehicleModel }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="card p-6 border-2 border-dashed border-gray-200">
                    <p class="text-sm text-gray-500 text-center">Geen auto gekoppeld</p>
                </div>
            @endif
        </div>

    </div>

@endsection
