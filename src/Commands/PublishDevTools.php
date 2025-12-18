<?php

declare(strict_types=1);

namespace Foodieneers\DevTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class PublishDevTools extends Command
{
    protected $signature = 'publish:devtools
                            {--force : Overwrite existing files without asking}
                            {--ask : Ask before overwriting files}';

    protected $description = 'Copy devtool config files from the package into this project';

    private array $files = [
        'pint' => 'pint.json',
        'peck' => 'peck.json',
        'phpstan' => 'phpstan.neon',
        'rector' => 'rector.php',
    ];

    public function handle(): int
    {
        $projectRoot = base_path();
        $stubsPath = realpath(__DIR__.'/../../stubs');

        foreach ($this->files as $source => $destination) {
            $sourcePath = implode(DIRECTORY_SEPARATOR, [$stubsPath, $source]);
            $destinationPath = implode(DIRECTORY_SEPARATOR, [$projectRoot, $destination]);

            if (File::exists($destinationPath)) {

                if ($this->option('force')) {
                    $this->overwrite($sourcePath, $destinationPath, $destination);

                    continue;
                }

                if ($this->option('ask') && $this->confirm("File {$destination} already exists. Overwrite?", false)) {
                    $this->overwrite($sourcePath, $destinationPath, $destination);

                    continue;
                }

                $this->line("Skipping existing {$destination}...");

                continue;
            }

            $this->overwrite($sourcePath, $destinationPath, $destination);
        }

        $this->info('Devtools publishing completed ✅');

        return self::SUCCESS;
    }

    private function overwrite(string $sourcePath, string $destinationPath, string $destination): void
    {
        $this->info("Publishing: {$destination}");
        File::copy($sourcePath, $destinationPath);
    }
}
