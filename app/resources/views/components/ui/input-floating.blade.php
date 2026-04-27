@props([
  'id',
  'name',
  'type' => 'text',
  'label' => '',
  'value' => '',
  'required' => false,
  'autocomplete' => '',
  'rows' => null,
])

<div class="relative">
  @if($rows)
    <textarea
      id="{{ $id }}"
      name="{{ $name }}"
      rows="{{ $rows }}"
      {{ $required ? 'required' : '' }}
      autocomplete="{{ $autocomplete }}"
      placeholder="{{ $label }}"
      class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
             focus:border-neutral-900 focus:ring-0 placeholder-transparent resize-none"
    >{{ old($name, $value) }}</textarea>
  @else
    <input
      type="{{ $type }}"
      id="{{ $id }}"
      name="{{ $name }}"
      value="{{ old($name, $value) }}"
      {{ $required ? 'required' : '' }}
      autocomplete="{{ $autocomplete }}"
      placeholder="{{ $label }}"
      class="peer block w-full border-0 border-b border-neutral-400 bg-transparent
             focus:border-neutral-900 focus:ring-0 placeholder-transparent"
    />
  @endif

  <label
    for="{{ $id }}"
    class="absolute left-0 -top-3.5 text-sm text-neutral-600 transition-all
           peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400
           peer-placeholder-shown:top-2 peer-focus:-top-3.5 peer-focus:text-sm peer-focus:text-neutral-900"
  >
    {{ $label }}
  </label>

  <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>
