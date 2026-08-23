<?php

declare(strict_types=1);

use Foodineers\DevTools\Tests\TestCase;

pest()->extend(TestCase::class)->in(__DIR__);

pest()->tia()->locally();
