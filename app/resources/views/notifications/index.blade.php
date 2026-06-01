<x-app-layout>

    <section class="relative overflow-hidden bg-[#333333]">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -right-40 -top-40 h-96 w-96 rounded-full bg-white opacity-[0.03] blur-3xl"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-6 py-16 sm:py-20 lg:px-8">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">Értesítések</h1>
            <p class="mt-3 text-neutral-400">A hozzád érkező rendszerüzenetek.</p>
        </div>
    </section>

    <div class="mx-auto max-w-3xl px-6 py-10 lg:px-8">

        @if($notifications->isEmpty())
            <div class="rounded-2xl border border-dashed border-neutral-200 bg-neutral-50 px-8 py-16 text-center">
                <x-icon name="bell" class="mx-auto mb-4 h-10 w-10 text-neutral-300" />
                <p class="text-sm text-neutral-400">Nincsenek értesítések.</p>
            </div>
        @else
            <div class="flex flex-col gap-3">
                @foreach($notifications as $notification)
                    @php
                        $wasUnread = $notification->read_at === null;
                        $shelter   = $notification->data['shelter_name'] ?? '';
                        $uuid      = $notification->data['shelter_uuid'] ?? '';
                    @endphp
                    <div @class([
                        'flex items-start gap-4 rounded-2xl px-6 py-5 ring-1',
                        'bg-white ring-black/5 shadow-sm'         => ! $wasUnread,
                        'bg-emerald-50 ring-emerald-200 shadow-sm' => $wasUnread,
                    ])>
                        <div @class([
                            'mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-full',
                            'bg-neutral-100' => ! $wasUnread,
                            'bg-emerald-100' => $wasUnread,
                        ])>
                            <x-icon name="user" @class(['h-4 w-4', 'text-emerald-600' => $wasUnread, 'text-neutral-400' => !$wasUnread]) />
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-neutral-900">
                                {{ $notification->data['message'] }}
                            </p>
                            @if($shelter && $uuid)
                                <a href="{{ route('shelters.show', $uuid) }}"
                                   class="mt-1 inline-block text-xs text-emerald-600 hover:underline">
                                    {{ $shelter }} megtekintése →
                                </a>
                            @endif
                            <p class="mt-2 text-xs text-neutral-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if($wasUnread)
                            <span class="mt-1 shrink-0 rounded-full bg-emerald-500 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white">
                                Új
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</x-app-layout>
