# AppSdk

[![Build Status](https://github.com/spryker-sdk/app-sdk/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/app-sdk/actions?query=workflow%3ACI+branch%3Amaster)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)

AppSdk is a tool that helps to work with Apps. It is designed as small Spryker project that has the same features as a standard installation.

There will be other modules beside the AppSdk module e.g. Console, Transfer etc.

## Installation

- `composer require --dev spryker-sdk/app-sdk`
- `vendor/bin/console transfer:generate`

### Run tests/checks

- `composer test` - This will execute the tests.
- `composer cs-check` - This will run CodeStyle checks.
- `composer cs-fix` - This will fix fixable CodeStyles.
- `composer stan` - This will run PHPStan checks.

## Documentation

- [Readiness Check](./docs/readiness-check.md)
- [Validation](./docs/validation.md)
- [AsyncAPI](./docs/async-api.md)
- [Configuration](./docs/configuration.md)
