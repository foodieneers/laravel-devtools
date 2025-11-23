<?php

declare(strict_types=1);

namespace Foodieneers\DevTools\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class DevToolsCommand extends Command
{
    protected $signature = 'publish:devtools {--force : Overwrite existing files without asking}';

    protected $description = 'Copy devtool config files from the package into this project';

    protected array $files = [
        'pint.json'        => 'pint.json',
        'peck.json'     => 'peck.json',
    ];

    public function handle(): int
    {
        $projectRoot = base_path();
        $stubsPath = realpath(__DIR__.'/../../stubs');

        foreach ($this->files as $sourceRelative => $destRelative) {
            $source = $stubsPath.'/'.$sourceRelative;
            $dest   = $projectRoot.'/'.$destRelative;
            
            if (! File::exists($source)) {
                $this->warn("Missing source file: {$sourceRelative} ({$source})");
                continue;
            }


            if (File::exists($dest) && ! $this->option('force')) {
                if (! $this->confirm("File {$destRelative} already exists. Overwrite?", false)) {
                    $this->line("Skipping {$destRelative}...");
                    continue;
                }
            }

            $this->info("Copying: {$sourceRelative} to {$destRelative}");
            File::ensureDirectoryExists(dirname($dest));
            File::copy($source, $dest);
        }

        $this->info('Devtools publishing completed ✅');

        return self::SUCCESS;
    }
}
