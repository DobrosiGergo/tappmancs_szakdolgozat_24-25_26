<x-app-layout>

    <div class="mx-auto max-w-3xl px-6 py-10 lg:px-8">

        <div class="mb-6 flex items-center gap-2 text-sm text-neutral-500">
            <a href="{{ route('messages.index') }}" class="transition hover:text-neutral-800">Üzenetek</a>
            <x-icon name="arrow-right" class="h-3.5 w-3.5 opacity-40" />
            <span class="text-neutral-800">Üzenet részletei</span>
        </div>

        <x-ui.card pad="p-8">

            <div class="mb-6 flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-neutral-100">
                        <x-icon name="user" class="h-6 w-6 text-neutral-400" />
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900">{{ $form->user?->name ?? 'Ismeretlen felhasználó' }}</p>
                        <p class="text-sm text-neutral-400">{{ $form->user?->email }}</p>
                    </div>
                </div>
                <span class="shrink-0 text-xs text-neutral-400">{{ $form->created_at->format('Y.m.d. H:i') }}</span>
            </div>

            @if($form->pet)
                <a href="{{ route('pets.show', $form->pet) }}"
                   class="mb-6 flex items-center gap-2.5 rounded-xl bg-neutral-50 px-4 py-3 ring-1 ring-neutral-200 transition hover:bg-white hover:ring-neutral-300">
                    <x-icon name="paw" class="h-4 w-4 shrink-0 text-neutral-400" />
                    <div class="min-w-0">
                        <p class="text-xs text-neutral-400">Kisállattal kapcsolatban</p>
                        <p class="truncate text-sm font-medium text-neutral-800">{{ $form->pet->name }}</p>
                    </div>
                    <x-icon name="arrow-right" class="ml-auto h-4 w-4 shrink-0 text-neutral-300" />
                </a>
            @endif

            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-neutral-400">Tárgy</div>
            <p class="mb-6 text-sm font-medium text-neutral-700">{{ $form->subject }}</p>

            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-neutral-400">Üzenet</div>
            <p class="whitespace-pre-line text-sm leading-7 text-neutral-800">{{ $form->message }}</p>

            <div class="mt-8 flex items-center justify-between gap-4 border-t border-neutral-100 pt-6">
                <a href="{{ route('messages.index') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-neutral-100 px-5 py-2.5 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-200">
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Vissza
                </a>

                <form method="POST" action="{{ route('messages.destroy', $form) }}"
                      x-data
                      @submit.prevent="if(confirm('Biztosan törlöd ezt az üzenetet?')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-600 ring-1 ring-red-200 transition hover:bg-red-100">
                        <x-icon name="delete" class="h-4 w-4" />
                        Törlés
                    </button>
                </form>
            </div>

        </x-ui.card>

    </div>

</x-app-layout>
