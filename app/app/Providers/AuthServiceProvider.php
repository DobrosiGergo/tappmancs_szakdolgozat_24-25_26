<?php

namespace App\Providers;

use App\Models\Shelter;
use App\Policies\ShelterPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Shelter::class => ShelterPolicy::class,
    ];
}
