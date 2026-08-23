# Changelog

All notable changes to `laravel-devtools` will be documented in this file.

<<<<<<< HEAD

## v1.6 - 2026-08-23

Moved namespace to Foodineers
+Github fix

## v1.5.0 - 2026-08-11

### Megafix

- New php artisan devtools:install (publish + scripts)
- README rewritten as the onboarding contract

#### Cleanup

- Removed dead Composer metadata (plugin class, facade, factories)
- Fully dropped Peck (stub, publish entry, docs)
- Removed placeholder ExampleTest

#### Stubs

- Pest, PHPUnit, and GitHub Actions workflow
- Relative cache paths + stronger PHPStan config
- npm scripts only when package.json exists

#### Tests

- Real filesystem coverage for publish/install
- Arch split out of the coverage run (it was skewing totals)

## Unreleased

- Add `devtools:install` to publish configs and Composer scripts in one step
- Publish Pest, PHPUnit, and GitHub Actions workflow stubs
- Drop Peck leftovers and dead Composer metadata (facade, Composer plugin, factories)
- Fix Rector/PHPStan cache paths; strengthen the PHPStan stub
- Only add npm Composer scripts when `package.json` exists
- # Rewrite README as the onboarding contract
  

## v1.4.0 - 2026-08-11

Pest v5

> > > > > > > 50c5b4c200e211ced42ff8cb9cfa1ff9c2aea150

## v1.3.0 - 2026-07-12

Added llm/skills

## v1.2.3 - 2026-06-06

Reverted signature and description from AddComposerScript

## v1.2.2 - 2026-04-30

Pao in correct dependency

**Full Changelog**: https://github.com/foodineers/laravel-devtools/compare/v1.2.1...v1.2.2

## v1.2.1 - 2026-04-30

### What's Changed

* Bump dependabot/fetch-metadata from 2.5.0 to 3.1.0 by @dependabot[bot] in https://github.com/foodineers/laravel-devtools/pull/9
* Update orchestra/testbench requirement from ^10.9.0||^9.0.0 to ^11.1.0 by @dependabot[bot] in https://github.com/foodineers/laravel-devtools/pull/8

Added pao
**Full Changelog**: https://github.com/foodineers/laravel-devtools/compare/v1.2.0...v1.2.1

## Removed peck - 2026-03-19

Removed peck

## Update to laravel 13 - 2026-03-19

Updated to laravel 13

## v1.0.1 - 2026-02-15

Updated boost to 2.1

## v1.0.0 - 2026-02-15

First release
