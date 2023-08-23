<?php

namespace SprykerSdk\Acp\Registrator;

use GuzzleHttp\Client;
use SprykerSdk\Acp\AcpConfig;
use Transfer\RegisterRequestTransfer;
use Transfer\RegisterResponseTransfer;
use GuzzleHttp\Cookie\CookieJar;

class Registrator implements RegistratorInterface
{
    /**
     * @param AcpConfig $config
     */
    public function __construct(protected AcpConfig $config)
    {
    }

    /**
     * @param RegisterRequestTransfer $registerRequestTransfer
     * @return RegisterResponseTransfer
     */
    public function register(RegisterRequestTransfer $registerRequestTransfer): RegisterResponseTransfer
    {
        $registerResponseTransfer = new RegisterResponseTransfer();

        $manifest = [
            "name" => "Hello World App",
            "provider" => "Example, Inc.",
            "description" => "Simple Hello World App for showcasing.",
            "descriptionShort" => "Hello World App.",
            "url" => "https://www.example.com",
            "isAvailable" => true,
            "categories" => [],
            "assets" => [
                [
                    "type" => "icon",
                    "url" => "/assets/img/hello-world/logo.png"
                ],
                [
                    "type" => "image",
                    "url" => "/assets/img/hello-world/gallery/image.png"
                ]
            ],
            "resources" => [
                [
                    "title" => "User Guide",
                    "url" => "https://docs.spryker.com/docs/acp/user/intro-to-acp/acp-overview.html",
                    "type" => "internal-documentation",
                    "fileType" => "spryker-docs"
                ]
            ],
            "pages" => [
                "Overview" => [
                ]
            ],
            "labels" => [
            ],
            "businessModels" => [
                "B2C",
                "B2B",
                "B2C_MARKETPLACE",
                "B2B_MARKETPLACE"
            ],
            "dependencies" => [
            ],
            "dialogs" => [
            ],
            "developedBy" => "Spryker Systems GmbH"
        ];

        $manifest = json_encode([
            'en_US' => $manifest,
            'de_DE' => $manifest,
        ]);
        $configuration = json_encode([
            "properties" => [
                "clientId" => [
                    "type" => "string",
                    "title" => "clientId_title",
                    "placeholder" => "clientId_placeholder",
                    "isRequired" => true,
                    "isLockable" => true,
                    "widget" => [
                        "id" => "password"
                    ]
                ],
                "clientSecret" => [
                    "type" => "string",
                    "title" => "clientSecret_title",
                    "placeholder" => "clientSecret_placeholder",
                    "isRequired" => true,
                    "isLockable" => true,
                    "widget" => [
                        "id" => "password"
                    ]
                ],
                "isActive" => [
                    "type" => "boolean",
                    "title" => "isActive_title",
                    "widget" => [
                        "id" => "app-status"
                    ],
                    "default" => false
                ]
            ],
            "fieldsets" => [
                [
                    "id" => "notifications",
                    "fields" => [
                        "isActive"
                    ],
                    "layout" => "noLayout"
                ],
                [
                    "id" => "configurations",
                    "title" => "configurations_fieldset_title",
                    "fields" => [
                        "clientId",
                        "clientSecret"
                    ],
                    "hint" => "configurations_hint"
                ]
            ],
            "required" => [
                "clientId",
                "clientSecret",
                "isActive"
            ]
        ]);
        $translation = json_encode([
            "properties" => [
                "clientId" => [
                    "type" => "string",
                    "title" => "clientId_title",
                    "placeholder" => "clientId_placeholder",
                    "isRequired" => true,
                    "isLockable" => true,
                    "widget" => [
                        "id" => "password"
                    ]
                ],
                "clientSecret" => [
                    "type" => "string",
                    "title" => "clientSecret_title",
                    "placeholder" => "clientSecret_placeholder",
                    "isRequired" => true,
                    "isLockable" => true,
                    "widget" => [
                        "id" => "password"
                    ]
                ],
                "isActive" => [
                    "type" => "boolean",
                    "title" => "isActive_title",
                    "widget" => [
                        "id" => "app-status"
                    ],
                    "default" => false
                ]
            ],
            "fieldsets" => [
                [
                    "id" => "notifications",
                    "fields" => [
                        "isActive"
                    ],
                    "layout" => "noLayout"
                ],
                [
                    "id" => "configurations",
                    "title" => "configurations_fieldset_title",
                    "fields" => [
                        "clientId",
                        "clientSecret"
                    ],
                    "hint" => "configurations_hint"
                ]
            ],
            "required" => [
                "clientId",
                "clientSecret",
                "isActive"
            ]
        ]);

        $api = json_encode([
            'configuration' => '/private/configure',
            'disconnection' => '/private/disconnect'
        ]);

        $body = [
            'data' => [
                'type' => 'apps',
                'attributes' => [
                    'id' => 'UUID of the App',
                    'baseUrl' => 'https://backend-api.de.mini-app.demo-spryker.com/',
                    'api' => $api,
                    'manifest' => $manifest,
                    'configuration' => $configuration,
                    'translation' => $translation,
                ],
            ],
        ];

//        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump(json_encode($body)) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();


        $cookieJar = CookieJar::fromArray([
            'XDEBUG_SESSION' => 'PHPSTORM',
            'path' => '/'
        ], 'example.com');

        $guzzleClient = new Client(['base_uri' => 'https://api.atrs.spryker.com']);
//        $guzzleClient = new Client(['base_uri' => 'http://glue.registry.spryker.local']);
        $response = $guzzleClient->post('/apps', [
            'body' => json_encode($body),
//            'cookies' => $cookieJar,
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IjI5ei15SUIyRmYwQnVEWS13dmhMdSJ9.eyJpc3MiOiJodHRwczovL3NwcnlrZXItcHJvZC5ldS5hdXRoMC5jb20vIiwic3ViIjoiYWhGeGdGUXVodUFRQlhlS2sxT1lSRHpCcW9aYXRqYzhAY2xpZW50cyIsImF1ZCI6ImFvcC1hdHJzIiwiaWF0IjoxNjkyNzk0MTI0LCJleHAiOjE2OTI4ODA1MjQsImF6cCI6ImFoRnhnRlF1aHVBUUJYZUtrMU9ZUkR6QnFvWmF0amM4Iiwic2NvcGUiOiJyZWdpc3RlcjphcHBfYXRycyByZWdpc3Rlcjp0ZW5hbnRfYXRycyByZWFkOnRlbmFudF9hdHJzIHJlYWRfcGlwZWxpbmU6YXBwX2F0cnMiLCJndHkiOiJjbGllbnQtY3JlZGVudGlhbHMiLCJwZXJtaXNzaW9ucyI6WyJyZWdpc3RlcjphcHBfYXRycyIsInJlZ2lzdGVyOnRlbmFudF9hdHJzIiwicmVhZDp0ZW5hbnRfYXRycyIsInJlYWRfcGlwZWxpbmU6YXBwX2F0cnMiXX0.PSVrcFu-prCQRgvd_8y9BxogEVZ0W9deDz0kukVQEmz3ak49H6P0MvDrkmCVIN0zd9i2FfV9VjD6yQC4NVa-BHGoHhBDt5K9fpU2EvLFs9Bg_fDjhR4rGDQ51gaDfLeRo5w5Yo_qmzo0GdhURHKJJ0CSQYX8wcZNQfHDcHAh2y8yxrE8dcRBCuDz70iDmjMBkzrI-sCvbFwbfXc0U_TjIgGq5hrI1WRmEvTO4Q8OKvRgU60Z_PQudNR1VpcDOdHNA9aIDc9Mw51l6PxAXcXACdEvMO4mPNCo9DFrOZwS_6xK_CSotv_jogc_l_RArEb60lUwVwptJhW2njkmHC7hZw',
            ]
        ]);

        return $registerResponseTransfer;
    }
}
