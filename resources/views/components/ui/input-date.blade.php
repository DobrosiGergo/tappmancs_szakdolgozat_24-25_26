@props([
  'id',
  'name',
  'label' => '',
  'value' => '',
  'required' => false,
  'min' => null,
  'max' => null,
])

<div class="flex flex-col gap-1.5">
  <label for="{{ $id }}" class="text-sm font-medium text-neutral-700">
    {{ $label }}
  </label>

  <div class="relative">
    <input
      type="date"
      id="{{ $id }}"
      name="{{ $name }}"
      value="{{ old($name, $value) }}"
      {{ $required ? 'required' : '' }}
      @if($min) min="{{ $min }}" @endif
      @if($max) max="{{ $max }}" @endif
      class="w-full rounded-xl border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900
             shadow-sm transition
             hover:border-neutral-400
             focus:border-neutral-900 focus:outline-none focus:ring-2 focus:ring-neutral-900/10
             [&::-webkit-calendar-picker-indicator]:cursor-pointer
             [&::-webkit-calendar-picker-indicator]:opacity-60
             [&::-webkit-calendar-picker-indicator]:hover:opacity-100"
    />
  </div>

  <x-input-error :messages="$errors->get($name)" class="mt-1" />
</div>