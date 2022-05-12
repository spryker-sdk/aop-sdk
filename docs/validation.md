# Validation

The validation console command runs checks for:

- manifest files
- translation files
- configuration files

and checks that those files are correct and can be used with the App Orchestration Platform (ACP).

You can run all at once or separated.

### Run all validations at once
`vendor/bin/acp app:validate`

#### Configuration options

The command offers the following configuration.

- `vendor/bin/acp app:validate -m path/to/manifest/files`
- `vendor/bin/acp app:validate -c path/to/configuration/file/configuration.json`
- `vendor/bin/acp app:validate -t path/to/translation/file/translation.json`

### Run manifest file validation
`vendor/bin/acp app:manifest:validate`

#### Configuration options

The command offers the following configuration.

`vendor/bin/acp app:manifest:validate -m path/to/manifest/files`

### Run configuration file validation
`vendor/bin/acp app:configuration:validate`

#### Configuration options

The command offers the following configuration.

`vendor/bin/acp app:configuration:validate -c path/to/configuration/file/configuration.json`


### Run translation file validation

---
**NOTE**

This validation needs the context of the manifest files to get locales you want to support and the configuration file that contains fields that need translations.

---

`vendor/bin/acp app:translation:validate`

#### Configuration options

The command offers the following configuration.

`vendor/bin/acp app:translation:validate -m path/to/manifest/files`
`vendor/bin/acp app:translation:validate -c path/to/configuration/file/configuration.json`
`vendor/bin/acp app:translation:validate -t path/to/translation/file/translation.json`
