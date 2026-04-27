@props([
  'leftLabel'  => 'Örökbefogadó',
  'rightLabel' => 'Menhely',
  'leftRole'   => 'User',
  'rightRole'  => 'shelter',
  'leftIcon'   => asset('images/profile.svg'),
  'rightIcon'  => asset('images/pet-shelter-svgrepo-com.svg'),
  'class'      => '',
])

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 {{ $class }}">
  <x-registration.role-card :role="$leftRole"  :label="$leftLabel"  :icon="$leftIcon"  />
  <x-registration.role-card :role="$rightRole" :label="$rightLabel" :icon="$rightIcon" />
</div>
