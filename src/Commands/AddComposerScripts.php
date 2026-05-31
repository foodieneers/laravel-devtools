<?php

declare(strict_types=1);

namespace Foodieneers\DevTools\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Description('Add standard lint and test scripts to composer.json')]
#[Signature('devtools:add-composer-scripts {--force : Overwrite existing scripts}')]
final class AddComposerScripts extends Command
{
    public function handle(): int
    {
        $path = base_path('composer.json');

        $composer = json_decode(
            file_get_contents($path),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $scripts = $composer['scripts'] ?? [];

        $scriptsToAdd = require __DIR__.'/../../stubs/composer-scripts.php';

        foreach ($scriptsToAdd as $name => $definition) {
            if (! array_key_exists((string) $name, $scripts) || $this->option('force')) {
                $scripts[$name] = $definition;
                $this->info(
                    $this->option('force')
                        ? "Overwritten script: {$name}"
                        : "Added script: {$name}"
                );
            } else {
                $this->warn("Skipped existing script: {$name}");
            }
        }

        $composer['scripts'] = $scripts;

        file_put_contents(
            $path,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
        );

        $this->info('composer.json updated successfully.');

        return self::SUCCESS;
    }
}
