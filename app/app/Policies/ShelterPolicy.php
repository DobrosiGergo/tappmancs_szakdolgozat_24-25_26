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

    public function manageStaff(User $user, Shelter $shelter): bool
    {
        return (int) $shelter->owner_id === (int) $user->id;
    }

    public function managePets(User $user, Shelter $shelter): bool
    {
        if ((int) $shelter->owner_id === (int) $user->id) {
            return true;
        }

        $worksAt = $user->worksAt;

        if (! $worksAt) {
            return false;
        }

        return (int) $worksAt->id === (int) $shelter->id;
    }
}
