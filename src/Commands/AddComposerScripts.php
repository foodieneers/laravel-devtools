<?php

declare(strict_types=1);

namespace Foodineers\DevTools\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Description('Add standard lint and test scripts to composer.json')]
#[Signature('devtools:composer-scripts {--force : Overwrite existing scripts}')]
final class AddComposerScripts extends Command
{
    public function handle(): int
    {
        $path = base_path('composer.json');

        if (! File::exists($path)) {
            $this->error('composer.json not found in the project root.');

            return self::FAILURE;
        }

        $composer = json_decode(
            File::get($path),
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $scripts = $composer['scripts'] ?? [];
        $scriptsToAdd = $this->scriptsToAdd();

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

        File::put(
            $path,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
        );

        $this->info('composer.json updated successfully.');

        return self::SUCCESS;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    private function scriptsToAdd(): array
    {
        /** @var array<string, array<int, string>|string> $scripts */
        $scripts = require __DIR__.'/../../stubs/composer-scripts.php';

        if (! File::exists(base_path('package.json'))) {
            return $scripts;
        }

        $scripts['dep:bump'] = [
            'composer bump',
            'npx npm-check-updates -u',
        ];

        $scripts['lint'] = [
            'rector',
            'pint --parallel',
            'npm run lint',
        ];

        $scripts['test:lint'] = [
            'pint --parallel --test',
            'rector --dry-run',
            'npm run test:lint',
        ];

        return $scripts;
    }
}
