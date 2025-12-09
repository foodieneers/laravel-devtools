<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;

it('copies files when they do not exist', function (): void {
    File::shouldReceive('exists')->andReturn(false);
    File::shouldReceive('copy');

    artisan('publish:devtools')
        ->expectsOutput('Devtools publishing completed ✅')
        ->assertSuccessful();
});

it('skips files when they exist and no flags are given', function (): void {
    File::shouldReceive('exists')->andReturn(true);

    File::shouldReceive('copy')->never();

    artisan('publish:devtools')
        ->expectsOutput('Skipping existing pint.json...')
        ->expectsOutput('Skipping existing peck.json...')
        ->expectsOutput('Skipping existing phpstan.neon...')
        ->assertSuccessful();
});

it('asks before overwriting when --ask is used', function (): void {
    File::shouldReceive('exists')->andReturn(true);

    File::shouldReceive('copy')->times(1);

    artisan('publish:devtools --ask')
        ->expectsQuestion('File pint.json already exists. Overwrite?', false)
        ->expectsQuestion('File peck.json already exists. Overwrite?', true)
        ->expectsQuestion('File phpstan.neon already exists. Overwrite?', false)
        ->expectsQuestion('File rector.php already exists. Overwrite?', false)
        ->assertSuccessful();
});

it('forces overwrite with --force', function (): void {
    File::shouldReceive('exists')->andReturn(true);

    File::shouldReceive('copy');

    artisan('publish:devtools --force')
        ->expectsOutput('Publishing: pint.json')
        ->expectsOutput('Publishing: peck.json')
        ->expectsOutput('Publishing: phpstan.neon')
        ->assertSuccessful();
});
