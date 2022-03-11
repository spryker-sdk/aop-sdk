# Validation

The validation console command runs checks for:

- manifest files
- translation files
- configuration files
- asyncapi file

and checks that those files are correct and can be used with the App Orchestration Platform (AOP).

You can run all at once or separated.

### Run all validations at once
`vendor/bin/app-sdk validate:app`

#### Configuration options

The command offers the following configuration.

- `vendor/bin/app-sdk validate:app -m path/to/manifest/files`
- `vendor/bin/app-sdk validate:app -c path/to/configuration/file/configuration.json`
- `vendor/bin/app-sdk validate:app -t path/to/translation/file/translation.json`
- `vendor/bin/app-sdk validate:app -a path/to/asyncapi/file/asyncapi.yml`

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

### Run asyncapi file validation
`vendor/bin/app-sdk validate:asyncapi`

#### Configuration options

The command offers the following configuration.

`vendor/bin/app-sdk validate:asyncapi -a path/to/asyncapi/file/asyncapi.yml`
