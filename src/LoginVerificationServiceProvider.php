<?php

namespace Braumye\LoginVerification;

use Braumye\LoginVerification\Controllers\VerificationController;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LoginVerificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/login-verification.php', 'login-verification');
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'login-verification');
    }

    public function boot(Filesystem $filesystem)
    {
        $this->publishes([
            __DIR__.'/../config/login-verification.php' => config_path('login-verification.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_login_verifications_tables.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'migrations');

        Route::post(
            $this->app->config->get('login-verification.routes.send'),
            [VerificationController::class, 'send']
        )->name('login-verification.send');

        Route::get(
            $this->app->config->get('login-verification.routes.confirm'),
            [VerificationController::class, 'confirm']
        )->name('login-verification.confirm');

        $this->registerResources();
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_login_verifications_tables.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_login_verifications_tables.php")
            ->first();
    }
}
