<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Betalingen overzicht') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto rounded-md">
                        <table class="w-full border border-gray-200 border-separate font-semibold mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Factuurnummer
                                    </th>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Bedrag
                                    </th>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Betaalmethode
                                    </th>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Transactie-referentie
                                    </th>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Status
                                    </th>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Actief
                                    </th>
                                    <th
                                        class="bg-gray-50 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        Toevoegen
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 border-t border-gray-100 align-middle whitespace-nowrap">
                                            {{ $payment->InvoiceNumber }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 border-t border-gray-100 align-middle whitespace-nowrap">
                                            €{{ number_format($payment->Amount, 2) }}</td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 border-t border-gray-100 align-middle whitespace-nowrap">
                                            {{ $payment->Method }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 border-t border-gray-100 align-middle">
                                            <span
                                                class="truncate max-w-[240px] inline-block align-middle">{{ $payment->TransactionRef }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm border-t border-gray-100 align-middle">
                                            @if ($payment->Status === 'Completed')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Succesvol
                                                </span>
                                            @elseif ($payment->Status === 'Pending')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    In afwachting
                                                </span>
                                            @elseif ($payment->Status === 'Refunded')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    Terugbetaald
                                                </span>
                                            @elseif ($payment->Status === 'Failed')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Mislukt
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($payment->Status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 border-t border-gray-100 align-middle text-center whitespace-nowrap">
                                            {{ $payment->IsActive ? 'Ja' : 'Nee' }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm text-gray-900 border-t border-gray-100 align-middle text-center whitespace-nowrap">
                                            <a href="{{ route('payment.create') }}">
                                                <button
                                                    class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                    Toevoegen
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="bg-amber-50 text-amber-700 text-center p-3">
                                                Er zijn geen betalingen om te tonen.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>