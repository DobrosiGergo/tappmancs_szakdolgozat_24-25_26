@props([
  'active' => null,
  'steps' => null,
  'class' => '',
  'role' => null,
  'roleShelter' => null,
])

@php
  if ($role) {
      $qRole = $role;
  } else {
      $qRole = request()->query('role');
  }

  if ($roleShelter) {
      $qShelterRole = $roleShelter;
  } else {
      $qShelterRole = request()->query('role_shelter');
  }

  if (!$qRole && auth()->check()) {
      $t = auth()->user()->type;
      if (in_array($t, ['shelterOwner','shelterWorker'])) {
          $qRole = 'shelter';
          $qShelterRole = $t;
      } elseif ($t === 'User') {
          $qRole = 'User';
      }
  }

  if ($steps === null) {
      if ($qRole === 'User') {
          $steps = [
              ['label' => 'Szerep', 'key' => 'role'],
              ['label' => 'Fiók',   'key' => 'account'],
          ];
      } elseif ($qRole === 'shelter' && $qShelterRole === 'shelterWorker') {
          $steps = [
              ['label' => 'Szerep',         'key' => 'role'],
              ['label' => 'Menhely szerep', 'key' => 'shelterRole'],
              ['label' => 'Fiók',           'key' => 'account'],
          ];
      } elseif ($qRole === 'shelter' && $qShelterRole === 'shelterOwner') {
          $steps = [
              ['label' => 'Szerep',         'key' => 'role'],
              ['label' => 'Menhely szerep', 'key' => 'shelterRole'],
              ['label' => 'Fiók',           'key' => 'account'],
              ['label' => 'Képek',          'key' => 'images'],
          ];
      } else {
          $steps = [
              ['label' => 'Szerep', 'key' => 'role'],
              ['label' => 'Fiók',   'key' => 'account'],
          ];
      }
  }

  $activeIndex = null;
  foreach ($steps as $i => $s) {
      if ($s['key'] === $active) {
          $activeIndex = $i;
          break;
      }
  }
@endphp

<div class="w-full pt-6 md:pt-10 {{ $class }}">
  <div class="max-w-5xl mx-auto overflow-x-auto">
    <ol class="inline-flex items-center gap-4 md:gap-8 text-sm min-w-max">
      @foreach ($steps as $idx => $step)
        @php
          $isDone   = $idx < $activeIndex;
          $isActive = $idx === $activeIndex;
        @endphp

        <li class="flex items-center">
          <div class="flex items-center gap-3">
            <span @class([
                'flex h-8 w-8 md:h-9 md:w-9 items-center justify-center rounded-full border text-[13px] md:text-[14px]',
                'bg-neutral-900 text-white border-neutral-900' => $isActive,
                'bg-neutral-200 text-neutral-700 border-neutral-200' => $isDone,
                'border-neutral-300 text-neutral-400' => !$isActive && !$isDone,
              ])>
              {{ $idx + 1 }}
            </span>

            <span @class(['font-semibold text-neutral-900' => $isActive, 'text-neutral-600' => !$isActive])>
              {{ $step['label'] }}
            </span>
          </div>

          @if (!$loop->last)
            <span class="mx-3 md:mx-6 h-px w-10 md:w-24 bg-neutral-200 block"></span>
          @endif
        </li>
      @endforeach
    </ol>
  </div>
</div>
