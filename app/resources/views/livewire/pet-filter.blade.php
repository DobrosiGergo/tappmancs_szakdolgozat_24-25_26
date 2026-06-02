<div>
    @foreach($filterGroups as $filter)
        <div class="mb-5">
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wide mb-2">{{ $filter['label'] }}</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($filter['options'] as $opt)
                    <button
                        wire:click="toggle('{{ $filter['name'] }}', '{{ $opt['value'] }}')"
                        @class([
                            'text-sm px-3 py-1.5 rounded-full border transition',
                            'bg-neutral-900 text-white border-neutral-900' => $this->{$filter['name']} === (string) $opt['value'],
                            'bg-white text-neutral-600 border-neutral-200 hover:border-neutral-400 hover:text-neutral-900' => $this->{$filter['name']} !== (string) $opt['value'],
                        ])
                    >
                        {{ $opt['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
