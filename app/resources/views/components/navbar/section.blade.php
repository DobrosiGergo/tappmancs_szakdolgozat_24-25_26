@props(['title' => null])
<div class="flex flex-col gap-2">
  @if($title)
    <div class="text-xs uppercase tracking-wide text-white/70 mb-1">{{ $title }}</div>
  @endif
  {{ $slot }}
</div>
