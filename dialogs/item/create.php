<?php

use MediumSans\LendManagement\Item;

return [
    'pattern' => 'inventory/item/create',
    'load'    => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'       => require __DIR__ . '/fields.php',
                'submitButton' => t('create'),
                'size'     => 'large',
            ],
        ];
    },
    'submit' => function () {
        return Item::create(get());
    }
];
