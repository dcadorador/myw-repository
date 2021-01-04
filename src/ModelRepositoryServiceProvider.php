<?php

namespace Myw\ModelRepository;

use Illuminate\Support\ServiceProvider;

class ModelRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            CreateBaseRepository::class,
            CreateModelRepository::class,
        ]);
    }
}
