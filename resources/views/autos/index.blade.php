@extends('layouts.app')
 
@section('title', 'Auto\'s')
@section('page-title', 'Voertuigen')
@section('breadcrumb', 'Overzicht van alle geregistreerde auto\'s')
 
@section('header-action')
    {{-- Admin en Instructeur kunnen auto's toevoegen --}}
    @if(auth()->check() && in_array(auth()->user()->rolename, ['admin', 'instructor']))
        <a href="{{ route('autos.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Auto toevoegen
        </a>
    @endif
@endsection
 
@section('content')
 
    {{-- 404 empty state --}}
    @if(empty($autos))
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white py-20 text-center">
            <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0M3 10l1.5-5h13L19 10M3 10h16"/>
            </svg>
            <p class="text-lg font-semibold text-gray-500">Error 404 – Geen voertuigen gevonden</p>
            <p class="text-sm text-gray-400 mt-1">Voeg je eerste auto toe om te beginnen.</p>
            <a href="{{ route('autos.create') }}" class="btn-primary mt-5">
                Auto toevoegen
            </a>
        </div>
 
    @else
        {{-- Responsive grid of vehicle cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
 
            @foreach($autos as $auto)
                <div class="card overflow-hidden hover:shadow-md transition-shadow group">
 
                    {{-- Kleurstrip bovenaan de kaart --}}
                    <div class="h-1.5 bg-indigo-600"></div>
 
                    <div class="p-5">
 
                        {{-- Licence plate badge --}}
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-block rounded-md bg-yellow-400 text-yellow-900 font-bold text-sm px-3 py-1 tracking-widest uppercase">
                                {{ $auto->LicensePlate }}
                            </span>
 
                            {{-- Availability badge --}}
                            @if($auto->IsAvailable)
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Beschikbaar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 text-red-700 text-xs font-medium px-2 py-0.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Niet beschikbaar
                                </span>
                            @endif
                        </div>
 
                        {{-- Brand + model --}}
                        <h2 class="text-base font-bold text-gray-900 leading-tight">
                            {{ $auto->Brand }} {{ $auto->Model }}
                        </h2>
 
                        {{-- Build year --}}
                        <p class="text-sm text-gray-500 mt-1">Bouwjaar: <span class="font-medium text-gray-700">{{ $auto->Year }}</span></p>
 
                        {{-- Linked instructor --}}
                        @if($auto->InstructorName)
                            <p class="text-sm text-gray-500 mt-0.5">
                                Instructeur: <span class="font-medium text-gray-700">{{ $auto->InstructorName }}</span>
                            </p>
                        @else
                            <p class="text-sm text-gray-400 mt-0.5 italic">Geen instructeur gekoppeld</p>
                        @endif
 
                    </div>
 
                    {{-- Card footer with action links --}}
                    <div class="border-t border-gray-100 px-5 py-3 flex items-center justify-end gap-3 bg-gray-50">
                        {{-- Admin en Instructeur kunnen bewerken en verwijderen --}}
                        @if(auth()->check() && in_array(auth()->user()->rolename, ['admin', 'instructor']))
                            <a href="{{ route('autos.edit', $auto->VehicleID) }}"
                               class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                Bewerken
                            </a>

                            {{-- Delete with confirmation --}}
                            <form action="{{ route('autos.destroy', $auto->VehicleID) }}" method="POST"
                                  onsubmit="return confirm('Weet je zeker dat je deze auto wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors">
                                    Verwijderen
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">Geen acties beschikbaar</span>
                        @endif
                    </div>
                    </div>
 
                </div>
            @endforeach
 
        </div>
    @endif
 
@endsection
 