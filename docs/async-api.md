# AsyncApi

The following console commands are available:

- `vendor/bin/app-sdk asyncapi:add`
- `vendor/bin/app-sdk asyncapi:add:message`

## Adding an AsyncAPI file

The `vendor/bin/app-sdk asyncapi:add` adds a minimal AsyncAPI file.

### Arguments and Options

#### Arguments

- `title`

`vendor/bin/app-sdk asyncapi:add "Your Async API title"` will set the title in your AsyncAPI file.

```
...
info:
    title: 'Your Async API title'
...
```

#### Options

- `asyncapi-file`
- `api-version`

`vendor/bin/app-sdk asyncapi:add --asyncapi-file "path/to/async-api.yml"` will override the default file location (config/api/asyncapi/asyncapi.yml).

`vendor/bin/app-sdk asyncapi:add --api-version 1.0.0` will override the default file version (0.1.0).

## Adding a message to an AsyncAPI file

The `vendor/bin/app-sdk asyncapi:add:message` adds a message to a given AsyncAPI file. This command can also be used to reverse engeneer from an existing Transfer object.

This console command has many options to be configured. See all of them by running

`vendor/bin/app-sdk asyncapi:add:message -h`

it will print a help page for this command.
