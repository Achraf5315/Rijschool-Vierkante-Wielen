@extends('layouts.app')

@section('title', 'Auto Details')
@section('page-title', $auto->Brand . ' ' . $auto->Model)
@section('breadcrumb', 'Overzicht van auto details')

@section('header-action')
    <div class="flex items-center gap-3">
        {{-- Admin en Instructeur kunnen bewerken --}}
        @if(auth()->check() && in_array(auth()->user()->rolename, ['admin', 'instructor']))
            <a href="{{ route('autos.edit', $auto->VehicleID) }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Bewerken
            </a>
        @endif
        <a href="{{ route('autos.index') }}"
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
            <div class="card overflow-hidden">

                {{-- Color strip --}}
                <div class="h-2 bg-indigo-600"></div>

                <div class="p-6 sm:p-8">

                    {{-- License plate badge --}}
                    <div class="flex items-baseline gap-4 mb-6 pb-6 border-b border-gray-200">
                        <span class="inline-block rounded-lg bg-yellow-400 text-yellow-900 font-bold text-2xl px-4 py-2 tracking-widest uppercase">
                            {{ $auto->LicensePlate }}
                        </span>
                        @if($auto->IsAvailable)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-sm font-medium px-3 py-1">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                Beschikbaar
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-100 text-red-700 text-sm font-medium px-3 py-1">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Niet beschikbaar
                            </span>
                        @endif
                    </div>

                    {{-- Vehicle information --}}
                    <div class="mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Voertuiggegevens</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Merk</span>
                                <span class="text-sm font-medium text-gray-900">{{ $auto->Brand }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Model</span>
                                <span class="text-sm font-medium text-gray-900">{{ $auto->Model }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Bouwjaar</span>
                                <span class="text-sm font-medium text-gray-900">{{ $auto->Year }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Status information --}}
                    <div class="mb-6">
                        <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Status</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Beschikbaarheid</span>
                                <div>
                                    @if($auto->IsAvailable)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            Beschikbaar
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 text-red-700 text-xs font-medium px-2.5 py-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Niet beschikbaar
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Delete action --}}
                    @if(auth()->check() && in_array(auth()->user()->rolename, ['admin', 'instructor']))
                        <div class="pt-6 border-t border-gray-200">
                            <form action="{{ route('autos.destroy', $auto->VehicleID) }}" method="POST"
                                  onsubmit="return confirm('Weet je zeker dat je deze auto permanent wilt verwijderen? Deze actie kan niet ongedaan gemaakt worden.')">
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
        </div>

        {{-- Sidebar --}}
        <div>
            {{-- Instructor info if linked --}}
            @if($auto->InstructorName)
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Gekoppelde instructeur</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-600 uppercase tracking-wider">Instructeur</p>
                            <p class="text-sm font-medium text-gray-900">{{ $auto->InstructorName }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="card p-6 border-2 border-dashed border-gray-200">
                    <p class="text-sm text-gray-500 text-center">Geen instructeur gekoppeld</p>
                </div>
            @endif
        </div>

    </div>

@endsection
