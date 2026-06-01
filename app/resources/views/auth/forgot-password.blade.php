<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-[440px]">

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-neutral-900">Elfelejtett jelszó</h2>
                <p class="mt-1 text-sm text-neutral-500">Add meg az e-mail címedet, és küldünk egy visszaállítási linket.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 rounded-xl bg-emerald-50 px-5 py-4 ring-1 ring-emerald-200">
                    <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-7">
                @csrf

                <x-ui.input-floating
                    id="email"
                    name="email"
                    type="email"
                    label="E-mail"
                    required="true"
                    value="{{ old('email') }}"
                />

                <x-primary-button class="w-full py-3 text-base rounded-xl !bg-[#333333]">
                    Link küldése
                </x-primary-button>
            </form>

            <p class="mt-6 text-center text-sm text-neutral-500">
                <a href="{{ route('login') }}" class="font-medium text-neutral-700 hover:text-neutral-900">Vissza a bejelentkezéshez</a>
            </p>

        </div>
    </div>
</x-guest-layout>
