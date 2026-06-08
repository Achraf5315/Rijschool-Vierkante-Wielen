{{--
    Flash-overlay voor succes- en foutmeldingen.

    Toont een nette banner bovenaan de pagina (echte UI, geen browser-popup).
    De melding verschijnt met een korte animatie en kan handmatig gesloten worden.
--}}
@if (session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition.opacity.duration.300ms
        class="fixed top-4 inset-x-0 z-50 flex justify-center px-4"
    >
        @if (session('success'))
            <div class="flex items-center gap-3 max-w-xl w-full bg-green-600 text-white px-4 py-3 rounded-md shadow-lg">
                <span class="flex-1 text-sm">{{ session('success') }}</span>
                <button type="button" @click="show = false" class="text-white/80 hover:text-white">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center gap-3 max-w-xl w-full bg-red-600 text-white px-4 py-3 rounded-md shadow-lg">
                <span class="flex-1 text-sm">{{ session('error') }}</span>
                <button type="button" @click="show = false" class="text-white/80 hover:text-white">&times;</button>
            </div>
        @endif
    </div>
@endif
