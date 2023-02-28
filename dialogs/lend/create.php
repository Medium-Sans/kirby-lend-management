<?php

use Kirby\LendManagement\Lend;

return [
    'pattern' => 'lendmanagement/lend/create',
    'load'    => function () {
        return [
            'component' => 'k-form-dialog',
            'props'     => [
                'fields'       => require __DIR__ . '/fields.php',
                'submitButton' => t('create'),
                'size' => 'large',
            ],
        ];
    },
    'submit' => function () {
        return Lend::create(get());
    }
];
