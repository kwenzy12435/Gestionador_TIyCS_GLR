<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\MonitoreoRed;
use App\Policies\MonitoreoRedPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ... otras políticas
        MonitoreoRed::class => MonitoreoRedPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}