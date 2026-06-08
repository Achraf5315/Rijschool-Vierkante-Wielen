{{-- Formulier om een bestaand lesrijpakket te bewerken (admin / instructeur). --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lesrijpakket bewerken: {{ $package->Name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card p-6">
                @include('driving-packages._form', [
                    'action' => route('driving-packages.update', $package),
                    'method' => 'PUT',
                    'package' => $package,
                ])
            </div>
        </div>
    </div>
</x-app-layout>
