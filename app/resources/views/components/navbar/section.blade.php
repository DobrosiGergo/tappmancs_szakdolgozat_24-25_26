@props(['title' => null])
<div class="flex flex-col gap-1.5">
  @if($title)
    <div class="text-[10px] font-bold uppercase tracking-widest text-white/40 mb-1.5">{{ $title }}</div>
  @endif
  {{ $slot }}
</div>
