# Laravel package for dev tools

All the php tooling and laravel tooling for handling applicatios development.

## Installation

You can install the package via composer:

```bash
composer require foodieneers/laravel-devtools --dev
```

You can publish all the tooling configs as:

```bash
php artisan publish:devtools
```

With the flag `--force` it will overwrite all existing files.

With the flag `--ask` it will overwrite depending on the answer of the user.

## Instructions

### Laravel Pint

```bash
./vendor/bin/pint
```

```bash
./vendor/bin/peck
```

## Testing

```bash
composer test
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
