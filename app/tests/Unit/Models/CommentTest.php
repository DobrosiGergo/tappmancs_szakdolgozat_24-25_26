<?php

use App\Models\Comment;
use App\Models\Pet;
use App\Models\User;

it('belongs to user and pet', function () {
    $comment = Comment::factory()->create();

    expect($comment->user)->toBeInstanceOf(User::class)
        ->and($comment->pet)->toBeInstanceOf(Pet::class);
});
