<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->projectRoot = base_path();
    $this->filePath = __DIR__;

    // File::put($this->basePath.'/pint.json', 'pint from base');
    // File::put($this->basePath.'/phpstan.neon.dist', 'phpstan from base');

    File::delete($this->projectRoot.'/pint.json');
    File::delete($this->projectRoot.'/phpstan.neon.dist');
});

it('copies config files from laravel-base when they do not exist in the project', function () {

    $this->artisan('publish:devtools')
        ->expectsOutputToContain('Copying:')
        ->expectsOutputToContain('Tooling completed.')
        ->assertExitCode(0);

    expect(File::exists($this->projectRoot.'/pint.json'))->toBeTrue();
    expect(File::exists($this->projectRoot.'/phpstan.neon.dist'))->toBeTrue();
});

it('asks before overwriting existing files and skips when answered no', function () {

    File::put($this->projectRoot.'/pint.json', 'local pint');
    File::put($this->projectRoot.'/phpstan.neon.dist', 'local phpstan');

    $this->artisan('publish:devtools')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'no')
        ->expectsConfirmation('File phpstan.neon.dist already exists. Overwrite?', 'no')
        ->expectsOutputToContain('Skipping pint.json...')
        ->expectsOutputToContain('Skipping phpstan.neon.dist...')
        ->assertExitCode(0);
});
