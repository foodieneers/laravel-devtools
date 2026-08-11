<?php

declare(strict_types=1);

namespace Foodieneers\DevTools\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Description('Copy Foodieneers devtool config files into this project')]
#[Signature('publish:devtools {--force : Overwrite existing files without asking} {--ask : Ask before overwriting files}')]
final class PublishDevTools extends Command
{
    /**
     * @var array<string, string>
     */
    private array $files = [
        'pint.json' => 'pint.json',
        'phpstan.neon' => 'phpstan.neon',
        'rector.php' => 'rector.php',
        'Pest.php' => 'tests/Pest.php',
        'phpunit.xml' => 'phpunit.xml',
        'github-workflow.yml' => '.github/workflows/tests.yml',
    ];

    public function handle(): int
    {
        $projectRoot = base_path();
        $stubsPath = __DIR__.'/../../stubs';

        if (! File::isDirectory($stubsPath)) {
            $this->error('Unable to locate package stubs directory.');

            return self::FAILURE;
        }

        $stubsPath = realpath($stubsPath);

        foreach ($this->files as $source => $destination) {
            $sourcePath = $stubsPath.DIRECTORY_SEPARATOR.$source;
            $destinationPath = $projectRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $destination);

            if (File::exists($destinationPath)) {
                if ($this->option('force')) {
                    $this->publish($sourcePath, $destinationPath, $destination);

                    continue;
                }

                if ($this->option('ask') && $this->confirm("File {$destination} already exists. Overwrite?", false)) {
                    $this->publish($sourcePath, $destinationPath, $destination);

                    continue;
                }

                $this->line("Skipping existing {$destination}...");

                continue;
            }

            $this->publish($sourcePath, $destinationPath, $destination);
        }

        $this->info('Devtools publishing completed.');

        return self::SUCCESS;
    }

    private function publish(string $sourcePath, string $destinationPath, string $destination): void
    {
        File::ensureDirectoryExists(dirname($destinationPath));
        $this->info("Publishing: {$destination}");
        File::copy($sourcePath, $destinationPath);
    }
}
