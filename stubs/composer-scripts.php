<?php

declare(strict_types=1);

return [
    'bump' => [
        'composer bump',
        'npx npm-check-updates -u'
    ],
    
    'lint' => [
        'rector',
        'pint --parallel',
        'npm run lint',
    ],

    'test:type-coverage' => 'pest --type-coverage --min=100',

    'test:lint' => [
        'pint --parallel --test',
        'rector --dry-run',
        'npm run test:lint',
    ],

    'pest' => 'pest',

    'test:unit' => 'pest --parallel --coverage --exactly=100.0',

    'test:types' => 'phpstan',

    'test' => [
        '@test:type-coverage',
        '@test:unit',
        '@test:lint',
        '@test:types',
    ],

];
