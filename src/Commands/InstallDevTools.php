<?php

declare(strict_types=1);

namespace Foodieneers\DevTools\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Install Foodieneers Laravel standard configs and Composer scripts')]
#[Signature('devtools:install {--force : Overwrite existing files and scripts without asking} {--ask : Ask before overwriting config files}')]
final class InstallDevTools extends Command
{
    public function handle(): int
    {
        $publishOptions = array_filter([
            '--force' => (bool) $this->option('force'),
            '--ask' => (bool) $this->option('ask'),
        ]);

        $publishStatus = $this->call('publish:devtools', $publishOptions);

        if ($publishStatus !== self::SUCCESS) {
            return $publishStatus;
        }

        $scriptsOptions = array_filter([
            '--force' => (bool) $this->option('force'),
        ]);

        return $this->call('devtools:composer-scripts', $scriptsOptions);
    }
}
