<?php

declare(strict_types=1);

return [
    'dep:bump' => [
        'composer bump',
    ],

    'lint' => [
        'rector',
        'pint --parallel',
    ],

    'test:type-coverage' => 'pest --type-coverage --min=100',

    'test:lint' => [
        'pint --parallel --test',
        'rector --dry-run',
    ],

    'pest' => 'pest',

    'test:unit' => [
        '@putenv XDEBUG_MODE=coverage',
        'pest --coverage --exactly=100.0',
    ],

    'test:arch' => 'pest tests/ArchTest.php',

    'test:types' => 'phpstan',

    'test' => [
        '@test:type-coverage',
        '@test:unit',
        '@test:arch',
        '@test:lint',
        '@test:types',
    ],
];
