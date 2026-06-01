@props([
    'shelter',
    'pet'      => null,
    'subtitle' => null,
])

@php
    $user           = auth()->user();
    $isShelterStaff = $user && in_array($user->type, ['Shelterowner', 'Shelterworker']);
    $isUser         = $user && $user->type === 'User';
    $alreadySent    = $pet && $isUser && \App\Models\Form::where('user_id', $user->id)->where('pet_id', $pet->id)->exists();
    $defaultSubtitle = 'Vedd fel a kapcsolatot a menhely koordinátorával.';
@endphp

@unless($isShelterStaff)
    <x-ui.card id="contact" class="scroll-mt-8" pad="p-8">

        <h2 class="mb-1 text-xl font-semibold text-neutral-900">Kapcsolatfelvétel</h2>
        <p class="mb-8 text-sm text-neutral-500">{{ $subtitle ?? $defaultSubtitle }}</p>

        @if($isUser)

            <div class="mb-6 flex items-center gap-3 rounded-xl bg-neutral-50 px-4 py-3 ring-1 ring-neutral-200">
                <div class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-neutral-200">
                    <x-icon name="user" class="h-4 w-4 text-neutral-500" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-neutral-800">{{ $user->name }}</p>
                    <p class="text-xs text-neutral-400">{{ $user->email }}</p>
                </div>
            </div>

            @if($alreadySent)

                <div class="rounded-xl bg-amber-50 px-5 py-4 ring-1 ring-amber-200">
                    <p class="text-sm font-medium text-amber-800">Már küldtél üzenetet ezzel a kisállattal kapcsolatban.</p>
                    <p class="mt-1 text-xs text-amber-600">A menhely koordinátora hamarosan felveszi veled a kapcsolatot.</p>
                </div>

                <div class="mt-5">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-400">
                        Üzeneted
                    </label>
                    <textarea rows="5" disabled
                              placeholder="Már küldtél üzenetet ezzel a kisállattal kapcsolatban."
                              class="w-full resize-none rounded-xl border border-neutral-200 bg-neutral-100 px-4 py-3 text-sm text-neutral-400 outline-none cursor-not-allowed"></textarea>
                </div>

            @else

                @if($errors->has('message'))
                    <div class="mb-6 rounded-xl bg-red-50 px-5 py-4 ring-1 ring-red-200">
                        <p class="text-sm text-red-700">{{ $errors->first('message') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('messages.store') }}"
                      x-data="{ loading: false }"
                      @submit="loading = true"
                      class="space-y-5">
                    @csrf
                    <input type="hidden" name="shelter_id" value="{{ $shelter->id }}">
                    @if($pet)
                        <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                    @endif

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-neutral-500">
                            Üzeneted
                        </label>
                        <textarea name="message" rows="5" required minlength="10" maxlength="2000"
                                  placeholder="Írj egy rövid bemutatkozást, és mondd el, miért szeretnéd örökbe fogadni ezt a kisállatot."
                                  class="w-full resize-none rounded-xl border border-neutral-200 bg-neutral-50 px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 outline-none transition focus:border-neutral-400 focus:bg-white focus:ring-2 focus:ring-neutral-200">{{ old('message') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-1">
                        <p class="text-xs text-neutral-400">
                            Adataidat kizárólag a kapcsolatfelvételhez használjuk.
                        </p>
                        <button type="submit"
                                class="inline-flex shrink-0 items-center gap-2 rounded-full bg-dark-mid px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-dark-soft disabled:opacity-60"
                                :disabled="loading">
                            <span x-show="!loading">Üzenet küldése</span>
                            <span x-show="loading" x-cloak>Küldés...</span>
                            <x-icon name="arrow-right" x-show="!loading" class="h-4 w-4 text-white" />
                        </button>
                    </div>
                </form>

            @endif

        @else
            <div class="rounded-xl border border-dashed border-neutral-200 bg-neutral-50 px-8 py-10 text-center">
                <x-icon name="mail" class="mx-auto mb-3 h-8 w-8 text-neutral-300" />
                <p class="text-sm font-medium text-neutral-600">Üzenet küldéséhez be kell jelentkezned.</p>
                <p class="mt-1 text-xs text-neutral-400">Csak regisztrált felhasználók küldhetnek üzenetet a menhelynek.</p>
                <a href="{{ route('login') }}"
                   class="mt-5 inline-flex items-center gap-2 rounded-full bg-dark-mid px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-dark-soft">
                    Bejelentkezés
                    <x-icon name="arrow-right" class="h-4 w-4 text-white" />
                </a>
            </div>
        @endif

    </x-ui.card>
@endunless
