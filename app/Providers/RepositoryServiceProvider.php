<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\EloquentUserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );
    }

    public function boot()
    {
        //
    }
}
