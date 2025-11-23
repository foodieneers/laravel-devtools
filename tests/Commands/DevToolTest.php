<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function () {
    $this->projectRoot = base_path();
    $this->filePath = __DIR__;

    File::delete($this->projectRoot.'/pint.json');
    File::delete($this->projectRoot.'/peck.json');
});

it('copies config files from stub when they do not exist in the project', function () {
    $this->artisan('publish:devtools')
        ->expectsOutputToContain('Copying:')
        ->expectsOutputToContain('Devtools publishing completed ✅')
        ->assertExitCode(0);

    expect(File::exists($this->projectRoot.'/pint.json'))->toBeTrue();
});

it('asks before overwriting existing files and skips when answered no', function () {

    File::put($this->projectRoot.'/pint.json', 'local pint');

    $this->artisan('publish:devtools')
        ->expectsConfirmation('File pint.json already exists. Overwrite?', 'no')
        ->expectsOutputToContain('Skipping pint.json...')
        ->assertExitCode(0);
});
