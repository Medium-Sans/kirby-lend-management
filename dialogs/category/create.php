<?php

use MediumSans\LendManagement\Category;

return [
    'pattern' => 'inventory/category/create',
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
        return Category::create(get());
    }
];
