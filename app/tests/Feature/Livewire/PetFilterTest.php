<?php

use App\Livewire\PetFilter;
use Livewire\Livewire;

it('pet filter component renders without errors', function () {
    Livewire::test(PetFilter::class)->assertOk();
});

it('toggle sets filter from empty to the given value', function () {
    Livewire::test(PetFilter::class)
        ->assertSet('gender', '')
        ->call('toggle', 'gender', 'male')
        ->assertSet('gender', 'male');
});

it('toggle clears filter when same value is toggled again', function () {
    Livewire::test(PetFilter::class)
        ->set('gender', 'male')
        ->call('toggle', 'gender', 'male')
        ->assertSet('gender', '');
});

it('toggle redirects after changing filter', function () {
    Livewire::test(PetFilter::class)
        ->call('toggle', 'status', 'free')
        ->assertRedirect();
});
