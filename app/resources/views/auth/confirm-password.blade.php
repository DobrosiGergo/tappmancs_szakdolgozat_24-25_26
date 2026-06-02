<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-[440px]">

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-neutral-900">Jelszó megerősítése</h2>
                <p class="mt-1 text-sm text-neutral-500">Ez egy biztonságos terület. A folytatáshoz erősítsd meg a jelszavadat.</p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-7">
                @csrf

                <x-ui.input-floating
                    id="password"
                    name="password"
                    type="password"
                    label="Jelszó"
                    required="true"
                />

                <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">
                    Megerősítés
                </x-primary-button>
            </form>

        </div>
    </div>
</x-guest-layout>
