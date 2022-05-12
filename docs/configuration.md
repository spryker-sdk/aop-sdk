# CreateConfiguration

The following console commands are available:

- `vendor/bin/acp app:configuration:create`

## Adding an Configuration file

The `vendor/bin/acp app:configuration:create` adds a minimal Configuration file.

#### Options

- `configuration-file`

`vendor/bin/acp app:configuration:create --configuration-file "path/to/configuration.json"` will override the default file location (config/app/configuration/configuration.json).

For each configuration you will be prompted to enter details.
When the process is done a configuration file will be created in: "path/to/configuration.json"
When you have a typo or anything else you'd like to change you can do that manually in the created file after this process is finished.

Only use translation keys for names. These fields need to be displayed in different languages.
