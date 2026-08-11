# Laravel DevTools

Foodieneers company-standard Laravel development toolkit. One `--dev` dependency that wires Pest, Pint, PHPStan/Larastan, Rector, and related tooling into every app the same way.

## Requirements

- PHP 8.5+
- Laravel 11, 12, or 13

## Installation

```bash
composer require foodieneers/laravel-devtools --dev
php artisan devtools:install
```

That publishes the standard configs and adds the Composer scripts. Useful flags:

- `--force` — overwrite existing config files and scripts
- `--ask` — confirm before overwriting each config file

You can still run the steps separately:

```bash
php artisan publish:devtools
php artisan devtools:composer-scripts
```

## What you get

| Tool | Role |
| --- | --- |
| Pest (+ Laravel, Arch, Type Coverage, Browser, Agent, Evals, PHPStan, Rector plugins) | Testing |
| Laravel Pint | Code style |
| Larastan / PHPStan | Static analysis |
| Rector (+ Laravel & Pest sets) | Automated refactors |
| Collision | Pretty CLI errors |
| Laravel Boost | AI-assisted Laravel development |
| Laravel Pao | Laravel PAO tooling |
| Spatie Ray | Local debugging |
| llm/skills | Shared LLM skills |

## Published files

| Source stub | Destination |
| --- | --- |
| `pint.json` | `pint.json` |
| `phpstan.neon` | `phpstan.neon` |
| `rector.php` | `rector.php` |
| `Pest.php` | `tests/Pest.php` |
| `phpunit.xml` | `phpunit.xml` |
| `github-workflow.yml` | `.github/workflows/tests.yml` |

Composer scripts are PHP-only by default. If the app has a `package.json`, `lint`, `test:lint`, and `dep:bump` also include the matching npm steps.

## Daily commands

```bash
composer test          # type-coverage + unit + arch + lint + types
composer lint          # rector + pint (+ npm lint when present)
composer test:unit
composer test:arch
composer test:types
composer test:lint
./vendor/bin/pint
./vendor/bin/pest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Azzarip](https://github.com/Azzarip)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
