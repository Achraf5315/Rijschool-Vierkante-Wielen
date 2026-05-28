<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Betaling aanmaken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('payment.store') }}"
                        class="grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
                        @csrf

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Factuurnummer</label>
                            <input name="invoice_number"
                                class="w-full border border-gray-200 rounded-md bg-gray-100 text-gray-500 cursor-default transition-colors px-3 py-2"
                                value="{{ old('invoice_number', 'FCT-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)) }}"
                                required maxlength="150" readonly>
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Bedrag</label>
                            <input type="number" name="amount"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2"
                                value="{{ old('amount') }}" required min="0" step="0.01">
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Transactiereferentie</label>
                            <input name="transaction_reference"
                                class="w-full border border-gray-200 rounded-md bg-gray-100 text-gray-500 cursor-default transition-colors px-3 py-2"
                                value="{{ old('transaction_reference', 'TRX-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)) }}"
                                required maxlength="150" readonly>
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Betaalmethode</label>
                            <select name="method"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2"
                                required>
                                <option value="">Kies een methode</option>
                                <option value="iDEAL" @selected(old('method') === 'iDEAL')>iDEAL</option>
                                <option value="CreditCard" @selected(old('method') === 'CreditCard')>Credit Card</option>
                                <option value="BankTransfer" @selected(old('method') === 'BankTransfer')>
                                    Bankoverschrijving</option>
                                <option value="Tikkie" @selected(old('method') === 'Tikkie')>Tikkie</option>
                            </select>
                        </div>

                        <div class="col-span-full flex justify-end gap-2">
                            <a href="{{ url()->previous() }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold transition transform hover:-translate-y-0.5 hover:shadow-md">Annuleren</a>
                            <button type="submit"
                                class="bg-gray-600 text-white px-4 py-2 rounded-md font-semibold transition transform hover:-translate-y-0.5 hover:shadow-md">Betaling
                                opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>