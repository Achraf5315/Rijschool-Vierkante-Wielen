@extends('layouts.app')
 
@section('title', 'Instructeurs')
@section('page-title', 'Instructeurs')
@section('breadcrumb', 'Overzicht van alle geregistreerde instructeurs')
 
@section('header-action')
    {{-- Solo admin puede agregar instructores --}}
    @if(auth()->check() && auth()->user()->rolename === 'admin')
        <a href="{{ route('instructeurs.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Instructeur toevoegen
        </a>
    @endif
@endsection
 
@section('content')
 
    {{-- 404 empty state --}}
    @if(empty($instructeurs))
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white py-20 text-center">
            <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a4 4 0 00-5-3.874M9 20H4v-2a4 4 0 015-3.874m6 5.874a4 4 0 10-8 0m4-10a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
            <p class="text-lg font-semibold text-gray-500">Error 404 – Geen instructeurs gevonden</p>
            <p class="text-sm text-gray-400 mt-1">Voeg je eerste instructeur toe om te beginnen.</p>
            <a href="{{ route('instructeurs.create') }}" class="btn-primary mt-5">
                Instructeur toevoegen
            </a>
        </div>
 
    @else
        {{-- Desktop table --}}
        <div class="hidden md:block card overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Naam</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Telefoon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gekoppelde auto</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acties</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($instructeurs as $instructeur)
                        <tr class="hover:bg-gray-50 transition-colors">
 
                            {{-- Full name --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ mb_strtoupper(mb_substr($instructeur->FirstName, 0, 1) . mb_substr($instructeur->LastName, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $instructeur->FirstName }} {{ $instructeur->LastName }}
                                    </span>
                                </div>
                            </td>
 
                            {{-- Email --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $instructeur->Email }}
                            </td>
 
                            {{-- Phone --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $instructeur->Phone }}
                            </td>
 
                            {{-- Active status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
 
                            {{-- Linked vehicle --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($instructeur->LicensePlate)
                                    <span class="inline-block rounded bg-yellow-400 text-yellow-900 font-bold text-xs px-2 py-0.5 tracking-wider uppercase mr-1">
                                        {{ $instructeur->LicensePlate }}
                                    </span>
                                    {{ $instructeur->VehicleBrand }} {{ $instructeur->VehicleModel }}
                                @else
                                    <span class="text-gray-400 italic">Geen auto</span>
                                @endif
                            </td>
 
                            {{-- Action links --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                {{-- Solo admin puede editar y eliminar --}}
                                @if(auth()->check() && auth()->user()->rolename === 'admin')
                                    <a href="{{ route('instructeurs.edit', $instructeur->InstructorID) }}"
                                       class="font-medium text-indigo-600 hover:text-indigo-800 mr-3 transition-colors">
                                        Bewerken
                                    </a>

                                    <form action="{{ route('instructeurs.destroy', $instructeur->InstructorID) }}" method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Weet je zeker dat je deze instructeur wilt verwijderen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="font-medium text-red-500 hover:text-red-700 transition-colors">
                                            Verwijderen
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">Geen acties beschikbaar</span>
                                @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
 
        {{-- Mobile card list --}}
        <div class="md:hidden space-y-4">
            @foreach($instructeurs as $instructeur)
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold">
                                {{ mb_strtoupper(mb_substr($instructeur->FirstName, 0, 1) . mb_substr($instructeur->LastName, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-900 text-sm">
                                {{ $instructeur->FirstName }} {{ $instructeur->LastName }}
                            </span>
                        </div>
                        @if($instructeur->IsActive)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Actief
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5">
                                Inactief
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">{{ $instructeur->Email }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $instructeur->Phone }}</p>
                    @if($instructeur->LicensePlate)
                        <p class="text-xs text-gray-500 mt-1">
                            Auto:
                            <span class="inline-block rounded bg-yellow-400 text-yellow-900 font-bold text-xs px-1.5 tracking-wider uppercase">
                                {{ $instructeur->LicensePlate }}
                            </span>
                            {{ $instructeur->VehicleBrand }} {{ $instructeur->VehicleModel }}
                        </p>
                    @endif
                    <div class="flex items-center justify-end gap-4 mt-4 pt-3 border-t border-gray-100">
                        {{-- Solo admin puede editar y eliminar --}}
                        @if(auth()->check() && auth()->user()->rolename === 'admin')
                            <a href="{{ route('instructeurs.edit', $instructeur->InstructorID) }}"
                               class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                Bewerken
                            </a>
                            <form action="{{ route('instructeurs.destroy', $instructeur->InstructorID) }}" method="POST"
                                  onsubmit="return confirm('Weet je zeker dat je deze instructeur wilt verwijderen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors">
                                    Verwijderen
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">Geen acties beschikbaar</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
 
@endsection