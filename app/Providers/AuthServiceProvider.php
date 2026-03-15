<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Shelter::class => \App\Policies\ShelterPolicy::class,
    ];

    public function boot(): void {}
}
