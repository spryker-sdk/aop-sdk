# CreateManifest

The following console commands are available:

- `vendor/bin/app-sdk app:manifest:create`

## Adding an Manifest file

The `vendor/bin/app-sdk app:manifest:create` adds a minimal Manifest file.

### Arguments

- `name`

`vendor/bin/app-sdk app:manifest:create "Your company name"` will set the name parameter of your Manifest file.


- `locale`

`vendor/bin/app-sdk app:manifest:create "Your company name" en_US ` will set the name parameter of your Manifest file and the locale will define the manifest file name.

Example:

path/to/manifest/en_US.json

```
{
    "name": "Your company name",
    "provider": "Your company name",
    "description": "",
    "descriptionShort": "",
    "configureUrl": "",
    "categories": [],
    "assets": [],
    "resources": [],
    "pages": [],
    "label": []
}    
```
