<?php

$stores = [
    'AopSdk' => [
        'locales' => [
            'en' => 'en_US',
            'de' => 'de_DE',
        ],
        // first entry is default
        'countries' => ['DE'],
        // internal and shop
        'currencyIsoCode' => 'EUR',
        'currencyIsoCodes' => ['EUR'],
    ],
];

// DE is the default store used by the Environment. We map the AopSdk store to the default one to be able to run
// Console commands e.g. `vendor/bin/console transfer:generate` without getting `Uncaught Exception: Missing setup for store: DE`
$stores['DE'] = $stores['AopSdk'];

return $stores;
