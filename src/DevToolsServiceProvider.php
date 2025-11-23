<?php

namespace Foodieneers\DevTools;

use Spatie\LaravelPackageTools\Package;
use Foodieneers\DevTools\Commands\DevToolsCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DevToolsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-devtools')
            ->hasCommand(DevToolsCommand::class);
    }
}
