<?php

declare(strict_types=1);

namespace Foodieneers\DevTools;

use Foodieneers\DevTools\Commands\AddComposerScripts;
use Foodieneers\DevTools\Commands\PublishDevTools;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class DevToolsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-devtools')
            ->hasCommand(PublishDevTools::class)
            ->hasCommand(AddComposerScripts::class);
    }
}
