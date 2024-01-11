# Register an App

Use the following console command to register or update your App in the App Store Catalog:

- `vendor/bin/acp app:register`

#### Options

To see all options this command provides run it with

`vendor/bin/acp app:register -h` this will print all available options.

The following options are required to be set:

- `appIdentifier`
- `baseUrl`
- `authorizationToken`

Additionally, you can set the following options:

- `apiBaseUrl` - Use this option when your App has an API that is not hosted on the same domain as the App itself.
