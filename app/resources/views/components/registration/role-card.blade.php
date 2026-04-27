@props([
  'action' => route('registration.store.role'),
  'role'   => 'User',
  'label'  => '',
  'icon'   => null,
])

<form method="POST" action="{{ $action }}" class="w-full">
  @csrf
  <button
    type="submit"
    name="role"
    value="{{ $role }}"
    aria-label="{{ $label }}"
    class="group w-full h-[220px] md:h-[240px] rounded-2xl border border-neutral-200 bg-white
           p-6 transition-colors duration-200
           hover:bg-neutral-50 hover:border-neutral-400
           focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2
           flex items-center gap-5"
  >
    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-neutral-100 text-neutral-700
                transition-colors group-hover:bg-neutral-200">
      @if($icon)
      <x-ui.lazy-image
        src="{{ $icon }}"
        alt=""
        class="h-7 w-7 opacity-90 group-hover:opacity-100"
        />
      @endif
    </div>

    <div class="text-left">
      <div class="text-lg md:text-xl font-semibold text-neutral-900">{{ $label }}</div>
      <p class="mt-1 text-sm text-neutral-600">Kattints a folytatáshoz</p>
    </div>
  </button>
</form>
