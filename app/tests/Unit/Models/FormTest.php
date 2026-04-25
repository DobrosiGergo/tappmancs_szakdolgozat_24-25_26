<?php

use App\Models\Form;
use App\Models\Pet;
use App\Models\Shelter;
use App\Models\User;

it('belongs to user, shelter and pet', function () {
    $form = Form::factory()->create();

    expect($form->user)->toBeInstanceOf(User::class)
        ->and($form->shelter)->toBeInstanceOf(Shelter::class)
        ->and($form->pet)->toBeInstanceOf(Pet::class);
});
