<?php

namespace App\Policies;

use App\Models\Shelter;
use App\Models\User;

class ShelterPolicy
{
    public function update(User $user, Shelter $shelter): bool
    {
        return (int) $shelter->owner_id === (int) $user->id;
    }
}
