<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Betaling aanmaken
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="payment-alert" class="hidden mb-4 rounded-md px-4 py-3 text-sm"></div>

                    <form id="payment-form" method="POST" action="{{ route('payment.store') }}"
                        class="grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
                        @csrf

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Klant</label>
                            <select id="client-select"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2"
                                required>
                                <option value="">Kies een klant</option>
                                @foreach ($invoices->unique('ClientId') as $invoice)
                                    <option value="{{ $invoice->ClientId }}"
                                        @selected(old('client_id') == $invoice->ClientId)>
                                        {{ $invoice->ClientName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Factuur</label>
                            <select name="invoice_id" id="invoice-select"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2"
                                required>
                                <option value="">Kies een factuur</option>
                                @foreach ($invoices as $invoice)
                                    <option value="{{ $invoice->Id }}" data-client-id="{{ $invoice->ClientId }}"
                                        data-amount="{{ $invoice->TotalAmount }}"
                                        @selected(old('invoice_id') == $invoice->Id)>
                                        {{ $invoice->InvoiceNumber }} - €{{ number_format($invoice->TotalAmount, 2) }} -
                                        {{ $invoice->Status }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($invoices->isEmpty())
                                <p class="mt-2 text-sm text-amber-700">Er zijn geen open facturen beschikbaar.</p>
                            @endif
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Bedrag</label>
                            <input type="number" name="amount" id="amount-input"
                                class="w-full border border-gray-200 bg-gray-100 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2 cursor-not-allowed"
                                value="{{ old('amount') }}" required min="0.01" step="0.01" readonly>
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Transactiereferentie</label>
                            <input name="transaction_ref"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2"
                                value="{{ old('transaction_ref', 'TRX-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)) }}"
                                required maxlength="150">
                        </div>

                        <div class="">
                            <label class="block font-semibold text-gray-800 mb-2">Betaalmethode</label>
                            <select name="method"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2"
                                required>
                                <option value="">Kies een methode</option>
                                <option value="iDEAL" @selected(old('method') === 'iDEAL')>iDEAL</option>
                                <option value="CreditCard" @selected(old('method') === 'CreditCard')>Creditcard</option>
                                <option value="BankTransfer" @selected(old('method') === 'BankTransfer')>
                                    Bankoverschrijving</option>
                                <option value="Cash" @selected(old('method') === 'Cash')>Contant</option>
                                <option value="Tikkie" @selected(old('method') === 'Tikkie')>Tikkie</option>
                            </select>
                        </div>

                        <div class="col-span-full">
                            <label class="block font-semibold text-gray-800 mb-2">Opmerking</label>
                            <textarea name="notes" rows="3"
                                class="w-full border border-gray-200 rounded-md transition-colors focus:border-blue-500 focus:shadow-outline px-3 py-2">{{ old('notes') }}</textarea>
                        </div>

                        <div class="col-span-full flex justify-end gap-2">
                            <a href="{{ url()->previous() }}"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold transition transform hover:-translate-y-0.5 hover:shadow-md">Annuleren</a>
                            <button type="submit" id="payment-submit"
                                class="bg-gray-600 text-white px-4 py-2 rounded-md font-semibold transition transform hover:-translate-y-0.5 hover:shadow-md">Betaling
                                opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('payment-form');
        const alertBox = document.getElementById('payment-alert');
        const clientSelect = document.getElementById('client-select');
        const invoiceSelect = document.getElementById('invoice-select');
        const amountInput = document.getElementById('amount-input');
        const submitButton = document.getElementById('payment-submit');
        const invoiceOptions = Array.from(invoiceSelect.options).map((option) => ({
            value: option.value,
            text: option.text,
            clientId: option.dataset.clientId || '',
            amount: option.dataset.amount || '',
        }));

        function updateAmountFromInvoice() {
            const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];

            if (!selectedOption || !selectedOption.dataset.amount) {
                amountInput.value = '';
                return;
            }

            amountInput.value = Number(selectedOption.dataset.amount).toFixed(2);
        }

        function filterInvoicesByClient(clientId) {
            invoiceSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Kies een factuur';
            invoiceSelect.appendChild(placeholder);

            const filteredInvoices = invoiceOptions.filter((option) => option.value && option.clientId === clientId);

            filteredInvoices.forEach((option) => {
                const invoiceOption = document.createElement('option');
                invoiceOption.value = option.value;
                invoiceOption.textContent = option.text;
                invoiceOption.dataset.clientId = option.clientId;
                invoiceOption.dataset.amount = option.amount;
                invoiceSelect.appendChild(invoiceOption);
            });

            if (filteredInvoices.length === 1) {
                invoiceSelect.value = filteredInvoices[0].value;
                updateAmountFromInvoice();
            } else {
                amountInput.value = '';
            }
        }

        clientSelect.addEventListener('change', function () {
            filterInvoicesByClient(this.value);
        });

        invoiceSelect.addEventListener('change', updateAmountFromInvoice);

        const initiallySelectedClient = clientSelect.value;
        if (initiallySelectedClient) {
            filterInvoicesByClient(initiallySelectedClient);

            const initialInvoiceId = @json(old('invoice_id'));
            if (initialInvoiceId) {
                invoiceSelect.value = initialInvoiceId;
            }

            updateAmountFromInvoice();
        }

        function showAlert(message, type) {
            alertBox.className = 'mb-4 rounded-md px-4 py-3 text-sm';
            if (type === 'success') {
                alertBox.classList.add('bg-green-50', 'text-green-800', 'border', 'border-green-200');
            } else {
                alertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
            }
            alertBox.textContent = message;
            alertBox.classList.remove('hidden');
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            alertBox.classList.add('hidden');
            submitButton.disabled = true;
            submitButton.textContent = 'Bezig...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });

                const payload = await response.json();
                if (!response.ok) {
                    const validationErrors = payload.errors ? Object.values(payload.errors).flat().join(' ') : '';
                    showAlert(payload.message || validationErrors || 'Er ging iets mis.', 'error');
                    // If duplicate (conflict), redirect back to payments overview after showing error
                    if (response.status === 409) {
                        setTimeout(() => {
                            window.location.href = '{{ route('payment.index') }}';
                        }, 1200);
                    }
                    return;
                }

                showAlert(payload.message || 'Betaling opgeslagen.', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route('payment.index') }}';
                }, 900);
            } catch (error) {
                showAlert('Kon de betaling niet opslaan.', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Betaling opslaan';
            }
        });
    </script>
</x-app-layout>