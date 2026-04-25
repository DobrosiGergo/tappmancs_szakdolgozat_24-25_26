@props([
  'id',
  'name',
  'label' => '',
  'value' => '',
  'required' => false,
  'options' => [],
])

@php
  $currentValue = old($name, $value);
  $currentLabel = collect($options)->firstWhere('value', $currentValue)['label'] ?? ($options[0]['label'] ?? '');
@endphp

<div class="flex flex-col gap-1.5">
  @if($label)
    <label for="{{ $id }}" class="text-sm font-medium text-neutral-700">{{ $label }}</label>
  @endif

  <div
    class="relative"
    x-data="{ open: false, val: '{{ $currentValue }}', lbl: '{{ $currentLabel }}' }"
    @click.outside="open = false"
  >
    <button
      type="button"
      id="{{ $id }}"
      @click="open = !open"
      :class="open ? 'border-neutral-900 ring-2 ring-neutral-900/10' : 'border-neutral-300 hover:border-neutral-400'"
      class="w-full flex items-center justify-between rounded-xl border bg-white px-4 py-3 text-sm text-neutral-900 shadow-sm transition focus:outline-none"
    >
      <span x-text="lbl" class="truncate text-left"></span>
      <img src="{{ asset('images/next.svg') }}" alt="" class="ml-2 h-4 w-4 shrink-0 rotate-90 transition-transform duration-200" :class="{ 'rotate-180': open }">
    </button>

    <input type="hidden" name="{{ $name }}" :value="val">

    <div
      x-show="open"
      x-transition:enter="transition ease-out duration-100"
      x-transition:enter-start="opacity-0 scale-95"
      x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-75"
      x-transition:leave-start="opacity-100 scale-100"
      x-transition:leave-end="opacity-0 scale-95"
      class="absolute z-20 mt-1 w-full overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-lg"
    >
      <div class="max-h-56 overflow-y-auto py-1">
        @foreach($options as $opt)
          <button
            type="button"
            @click="val = '{{ $opt['value'] }}'; lbl = '{{ $opt['label'] }}'; open = false"
            :class="val === '{{ $opt['value'] }}' ? 'bg-neutral-900 text-white' : 'text-neutral-700 hover:bg-neutral-30'"
            class="flex w-full items-center justify-between px-4 py-2.5 text-sm transition"
          >
            <span>{{ $opt['label'] }}</span>
          </button>
        @endforeach
      </div>
    </div>
  </div>

  <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>
