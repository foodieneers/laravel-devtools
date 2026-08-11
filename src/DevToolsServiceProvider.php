<?php

declare(strict_types=1);

namespace Foodieneers\DevTools;

use Foodieneers\DevTools\Commands\AddComposerScripts;
use Foodieneers\DevTools\Commands\InstallDevTools;
use Foodieneers\DevTools\Commands\PublishDevTools;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class DevToolsServiceProvider extends PackageServiceProvider
{
    #[Override]
    public $app;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-devtools');
    }

    public function bootingPackage(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddComposerScripts::class,
                InstallDevTools::class,
                PublishDevTools::class,
            ]);
        }
    }
}
