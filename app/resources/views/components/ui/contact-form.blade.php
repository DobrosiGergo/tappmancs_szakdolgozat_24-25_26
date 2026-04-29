@props([
    'subtitle' => 'Vedd fel a kapcsolatot a menhely koordinátorával. Általában 1-3 munkanapon belül válaszolnak.',
])

@php
    $isShelterStaff = auth()->check() && in_array(auth()->user()->type, ['Shelterowner', 'Shelterworker']);
@endphp

@unless($isShelterStaff)
    <x-ui.card id="contact" data-reveal class="scroll-mt-8" pad="p-8" x-data="{ sent: false, loading: false }">

        <h2 class="mb-1 text-xl font-semibold text-neutral-900">Kapcsolatfelvétel</h2>
        <p class="mb-8 text-sm text-neutral-500">{{ $subtitle }}</p>

        <div x-show="sent" x-cloak class="rounded-xl bg-emerald-50 px-6 py-8 text-center ring-1 ring-emerald-200">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100">
                <img src="{{ asset('images/check.svg') }}" alt="" class="h-5 w-5">
            </div>
            <p class="text-base font-semibold text-emerald-800">Üzenet elküldve!</p>
            <p class="mt-1 text-sm text-emerald-600">
                Köszönjük, hamarosan felveszi veled a kapcsolatot a menhely koordinátora.
            </p>
        </div>

        <form x-show="!sent" x-cloak
              @submit.prevent="
                  loading = true;
                  setTimeout(() => { loading = false; sent = true; }, 800);
              "
              class="space-y-5">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        Neved
                    </label>
                    <input type="text" required placeholder="Bela Bela"
                           class="w-full rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 outline-none transition focus:border-neutral-400 focus:bg-white focus:ring-2 focus:ring-neutral-200">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        E-mail
                    </label>
                    <input type="email" required placeholder="bela@pelda.hu"
                           class="w-full rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 outline-none transition focus:border-neutral-400 focus:bg-white focus:ring-2 focus:ring-neutral-200">
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    Telefon
                    <span class="ml-1 font-normal normal-case text-neutral-400">(opcionális)</span>
                </label>
                <input type="tel" placeholder="+36 70 987 6543"
                       class="w-full rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 outline-none transition focus:border-neutral-400 focus:bg-white focus:ring-2 focus:ring-neutral-200">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    Üzeneted
                </label>
                <textarea rows="4" required
                          placeholder="Írj egy rövid bemutatkozást magadról - milyen körülmények között élsz, van-e korábbi tapasztalatod állattartásban, és melyik kisállat keltette fel az érdeklődésedet."
                          class="w-full resize-none rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 outline-none transition focus:border-neutral-400 focus:bg-white focus:ring-2 focus:ring-neutral-200"></textarea>
            </div>

            <div class="flex items-center justify-between gap-4 pt-1">
                <p class="text-xs text-neutral-400">
                    Adataidat kizárólag a kapcsolatfelvételhez használjuk.
                </p>
                <button type="submit"
                        class="inline-flex shrink-0 items-center gap-2 rounded-full bg-[#333333] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-neutral-700 disabled:opacity-60"
                        :disabled="loading">
                    <span x-show="!loading">Üzenet küldése</span>
                    <span x-show="loading" x-cloak>Küldés...</span>
                    <img x-show="!loading" src="{{ asset('images/next.svg') }}" alt="" class="h-4 w-4 brightness-0 invert">
                </button>
            </div>
        </form>
    </x-ui.card>
@endunless
