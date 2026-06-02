<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-[440px]">

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-neutral-900">E-mail hitelesítés</h2>
                <p class="mt-2 text-sm text-neutral-500">
                    Köszönjük a regisztrációt! Mielőtt továbblépnél, kérjük, erősítsd meg az e-mail címedet az elküldött levélben lévő link segítségével.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 rounded-xl bg-emerald-50 px-5 py-4 ring-1 ring-emerald-200">
                    <p class="text-sm font-medium text-emerald-800">Egy új hitelesítő linket küldtünk az e-mail címedre.</p>
                </div>
            @endif

            <div class="flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">
                        Hitelesítő e-mail újraküldése
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full py-3 text-sm font-medium text-neutral-500 hover:text-neutral-900 transition">
                        Kijelentkezés
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>
