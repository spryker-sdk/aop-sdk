# AppSdk

[![Build Status](https://github.com/spryker-sdk/app-sdk/workflows/CI/badge.svg?branch=master)](https://github.com/spryker-sdk/app-sdk/actions?query=workflow%3ACI+branch%3Amaster)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)

AppSdk is a tool that helps to work with Apps. It is designed as small Spryker project that has the same features as a standard installation.

There will be other modules beside the AppSdk module e.g. Console, Transfer etc.

## Installation

`composer require --dev spryker-sdk/app-sdk`

`vendor/bin/console transfer:generate`

### Run tests/checks

- `composer test` - This will execute the tests.
- `composer cs-check` - This will run CodeStyle checks.
- `composer cs-fix` - This will fix fixable CodeStyles.
- `composer stan` - This will run PHPStan checks.

## Usage

### Run all validations at once
`vendor/bin/app-sdk validate:app`

#### Configuration options

The command offers the following configuration.

`vendor/bin/app-sdk validate:app -m path/to/manifest/files`
`vendor/bin/app-sdk validate:app -c path/to/configuration/file/configuration.json`
`vendor/bin/app-sdk validate:app -t path/to/translation/file/translation.json`

### Run manifest file validation
`vendor/bin/app-sdk validate:manifest`

#### Configuration options

The command offers the following configuration.

`vendor/bin/app-sdk validate:manifest -m path/to/manifest/files`

### Run configuration file validation
`vendor/bin/app-sdk validate:configuration`

#### Configuration options

The command offers the following configuration.

`vendor/bin/app-sdk validate:configuration -c path/to/configuration/file/configuration.json`


### Run translation file validation

---
**NOTE**

This validation needs the context of the manifest files to get locales you want to support and the configuration file that contains fields that need translations.

---

`vendor/bin/app-sdk validate:translation`

#### Configuration options

The command offers the following configuration.

`vendor/bin/app-sdk validate:translation -m path/to/manifest/files`
`vendor/bin/app-sdk validate:translation -c path/to/configuration/file/configuration.json`
`vendor/bin/app-sdk validate:translation -t path/to/translation/file/translation.json`

