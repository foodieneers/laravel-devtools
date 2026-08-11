<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

beforeEach(function (): void {
    $this->tmpBase = storage_path('framework/testing/tmp-'.uniqid('', true));
    File::ensureDirectoryExists($this->tmpBase);
    app()->setBasePath($this->tmpBase);

    File::put($this->tmpBase.'/composer.json', json_encode([
        'name' => 'test/test',
        'scripts' => [],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);
});

afterEach(function (): void {
    if (property_exists($this, 'tmpBase') && $this->tmpBase !== null && File::exists($this->tmpBase)) {
        File::deleteDirectory($this->tmpBase);
    }
});

it('stops when publishing fails', function (): void {
    File::partialMock()
        ->shouldReceive('isDirectory')
        ->once()
        ->andReturn(false);

    artisan('devtools:install')
        ->expectsOutput('Unable to locate package stubs directory.')
        ->assertFailed();

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($composer['scripts'] ?? [])->toBe([]);
});

it('publishes configs and adds composer scripts', function (): void {
    artisan('devtools:install')
        ->assertSuccessful();

    expect(File::exists($this->tmpBase.'/pint.json'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/phpstan.neon'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/rector.php'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/tests/Pest.php'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/phpunit.xml'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/.github/workflows/tests.yml'))->toBeTrue();

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($composer['scripts'])->toHaveKeys([
        'dep:bump',
        'lint',
        'test:type-coverage',
        'test:lint',
        'pest',
        'test:unit',
        'test:arch',
        'test:types',
        'test',
    ]);
});

it('respects --force for both configs and scripts', function (): void {
    File::put($this->tmpBase.'/pint.json', 'old');

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);
    $composer['scripts']['lint'] = ['echo "old"'];
    File::put(
        $this->tmpBase.'/composer.json',
        json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
    );

    artisan('devtools:install --force')
        ->assertSuccessful();

    expect(File::get($this->tmpBase.'/pint.json'))->not->toBe('old');

    $composer = json_decode(File::get($this->tmpBase.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);

    expect($composer['scripts']['lint'])->toBe([
        'rector',
        'pint --parallel',
    ]);
});
