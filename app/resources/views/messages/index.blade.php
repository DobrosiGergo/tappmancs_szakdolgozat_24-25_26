<x-app-layout>

    <section class="relative overflow-hidden bg-[#333333]">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-white opacity-[0.03] blur-3xl"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-6 py-16 sm:py-20 lg:px-8">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">Beérkező üzenetek</h1>
            <p class="mt-3 text-neutral-400">{{ $shelter->name }} – felhasználói megkeresések</p>
        </div>
    </section>

    <div class="mx-auto max-w-4xl px-6 py-10 lg:px-8">

        @if($messages->isEmpty())
            <div class="rounded-2xl border border-dashed border-neutral-200 bg-neutral-50 px-8 py-16 text-center">
                <x-icon name="mail" class="mx-auto mb-4 h-10 w-10 text-neutral-300" />
                <p class="text-sm font-medium text-neutral-500">Még nem érkezett üzenet.</p>
                <p class="mt-1 text-xs text-neutral-400">Ha a felhasználók üzenetet küldenek a menhelyednek, itt fognak megjelenni.</p>
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach($messages as $message)
                    <a href="{{ route('messages.show', $message) }}"
                       class="group flex items-start gap-4 rounded-2xl bg-white px-6 py-5 ring-1 ring-black/5 shadow-sm transition hover:shadow-md hover:ring-black/10">

                        <div class="mt-0.5 grid h-10 w-10 shrink-0 place-items-center rounded-full bg-neutral-100">
                            <x-icon name="user" class="h-5 w-5 text-neutral-400" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-3">
                                <p class="truncate text-sm font-semibold text-neutral-900">
                                    {{ $message->user?->name ?? 'Ismeretlen felhasználó' }}
                                </p>
                                <span class="shrink-0 text-xs text-neutral-400">
                                    {{ $message->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mt-0.5 truncate text-sm text-neutral-500">{{ $message->subject }}</p>
                            @if($message->pet)
                                <span class="mt-1.5 inline-flex items-center gap-1.5 rounded-full bg-neutral-100 px-2.5 py-0.5 text-xs text-neutral-500">
                                    <x-icon name="paw" class="h-3 w-3" />
                                    {{ $message->pet->name }}
                                </span>
                            @endif
                        </div>

                        <x-icon name="arrow-right" class="mt-1 h-4 w-4 shrink-0 text-neutral-300 transition group-hover:translate-x-0.5 group-hover:text-neutral-500" />
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $messages->links() }}
            </div>
        @endif

    </div>

</x-app-layout>
