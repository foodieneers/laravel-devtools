<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

beforeEach(function (): void {
    $this->tmpBase = storage_path('framework/testing/tmp-'.uniqid('', true));
    $this->scripts = require __DIR__.'/../../stubs/composer-scripts.php';

    File::ensureDirectoryExists($this->tmpBase);
    app()->setBasePath($this->tmpBase);
});

afterEach(function (): void {
    if (property_exists($this, 'tmpBase') && $this->tmpBase !== null && File::exists($this->tmpBase)) {
        File::deleteDirectory($this->tmpBase);
    }
});

it('adds the scripts when missing', function (): void {
    File::put($this->tmpBase.'/composer.json', json_encode([
        'name' => 'test/test',
        'scripts' => [],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

    artisan('devtools:add-composer-scripts')
        ->assertExitCode(0)
        ->expectsOutput('composer.json updated successfully.');

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($composer['scripts'])->toHaveKeys(array_keys($this->scripts));
});

it('does not overwrite existing scripts by default', function (): void {
    File::put($this->tmpBase.'/composer.json', json_encode([
        'name' => 'test/test',
        'scripts' => [
            'lint' => ['echo "keep me"'],
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

    artisan('devtools:add-composer-scripts')
        ->assertExitCode(0)
        ->expectsOutput('Skipped existing script: lint');

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($composer['scripts']['lint'])->toBe(['echo "keep me"']);
});

it('overwrites existing scripts with --force', function (): void {
    File::put($this->tmpBase.'/composer.json', json_encode([
        'name' => 'test/test',
        'scripts' => [
            'lint' => ['echo "old"'],
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

    artisan('devtools:add-composer-scripts --force')
        ->assertExitCode(0)
        ->expectsOutput('Overwritten script: lint');

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($composer['scripts']['lint'])->toBe($this->scripts['lint']);
});
