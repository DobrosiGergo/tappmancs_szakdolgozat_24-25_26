<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-[440px]">

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-neutral-900">Jelszó visszaállítása</h2>
                <p class="mt-1 text-sm text-neutral-500">Add meg az új jelszavadat az alábbi mezőkben.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-7">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <x-ui.input-floating
                    id="email"
                    name="email"
                    type="email"
                    label="E-mail"
                    required="true"
                    value="{{ old('email', $request->email) }}"
                />

                <x-ui.input-floating
                    id="password"
                    name="password"
                    type="password"
                    label="Új jelszó"
                    required="true"
                />

                <x-ui.input-floating
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    label="Jelszó megerősítése"
                    required="true"
                />

                <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">
                    Jelszó visszaállítása
                </x-primary-button>
            </form>

        </div>
    </div>
</x-guest-layout>
