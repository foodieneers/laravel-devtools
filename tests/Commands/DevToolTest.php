<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

beforeEach(function (): void {
    $this->tmpBase = storage_path('framework/testing/tmp-'.uniqid('', true));
    File::ensureDirectoryExists($this->tmpBase);
    app()->setBasePath($this->tmpBase);
});

afterEach(function (): void {
    if (property_exists($this, 'tmpBase') && $this->tmpBase !== null && File::exists($this->tmpBase)) {
        File::deleteDirectory($this->tmpBase);
    }
});

it('fails when the stubs directory is missing', function (): void {
    File::partialMock()
        ->shouldReceive('isDirectory')
        ->once()
        ->andReturn(false);

    artisan('publish:devtools')
        ->expectsOutput('Unable to locate package stubs directory.')
        ->assertFailed();
});

it('copies files when they do not exist', function (): void {
    artisan('publish:devtools')
        ->expectsOutput('Devtools publishing completed.')
        ->assertSuccessful();

    expect(File::exists($this->tmpBase.'/pint.json'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/phpstan.neon'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/rector.php'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/tests/Pest.php'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/phpunit.xml'))->toBeTrue()
        ->and(File::exists($this->tmpBase.'/.github/workflows/tests.yml'))->toBeTrue();
});

it('skips files when they exist and no flags are given', function (): void {
    File::put($this->tmpBase.'/pint.json', '{"custom":true}');
    File::ensureDirectoryExists($this->tmpBase.'/tests');
    File::put($this->tmpBase.'/tests/Pest.php', '<?php // custom');

    artisan('publish:devtools')
        ->expectsOutput('Skipping existing pint.json...')
        ->expectsOutput('Skipping existing tests/Pest.php...')
        ->assertSuccessful();

    expect(File::get($this->tmpBase.'/pint.json'))->toBe('{"custom":true}')
        ->and(File::get($this->tmpBase.'/tests/Pest.php'))->toBe('<?php // custom')
        ->and(File::exists($this->tmpBase.'/phpstan.neon'))->toBeTrue();
});

it('asks before overwriting when --ask is used', function (): void {
    foreach ([
        'pint.json',
        'phpstan.neon',
        'rector.php',
        'phpunit.xml',
    ] as $file) {
        File::put($this->tmpBase.'/'.$file, 'existing');
    }

    File::ensureDirectoryExists($this->tmpBase.'/tests');
    File::put($this->tmpBase.'/tests/Pest.php', 'existing');
    File::ensureDirectoryExists($this->tmpBase.'/.github/workflows');
    File::put($this->tmpBase.'/.github/workflows/tests.yml', 'existing');

    artisan('publish:devtools --ask')
        ->expectsQuestion('File pint.json already exists. Overwrite?', false)
        ->expectsQuestion('File phpstan.neon already exists. Overwrite?', true)
        ->expectsQuestion('File rector.php already exists. Overwrite?', false)
        ->expectsQuestion('File tests/Pest.php already exists. Overwrite?', false)
        ->expectsQuestion('File phpunit.xml already exists. Overwrite?', false)
        ->expectsQuestion('File .github/workflows/tests.yml already exists. Overwrite?', false)
        ->assertSuccessful();

    expect(File::get($this->tmpBase.'/pint.json'))->toBe('existing')
        ->and(File::get($this->tmpBase.'/phpstan.neon'))->not->toBe('existing');
});

it('forces overwrite with --force', function (): void {
    File::put($this->tmpBase.'/pint.json', 'old');

    artisan('publish:devtools --force')
        ->expectsOutput('Publishing: pint.json')
        ->expectsOutput('Publishing: phpstan.neon')
        ->expectsOutput('Publishing: rector.php')
        ->expectsOutput('Publishing: tests/Pest.php')
        ->expectsOutput('Publishing: phpunit.xml')
        ->expectsOutput('Publishing: .github/workflows/tests.yml')
        ->assertSuccessful();

    expect(File::get($this->tmpBase.'/pint.json'))->not->toBe('old')
        ->and(json_decode(File::get($this->tmpBase.'/pint.json'), true)['preset'])->toBe('laravel');
});
