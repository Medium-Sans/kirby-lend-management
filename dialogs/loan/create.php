<?php

use Kirby\LendManagement\Loan;

return [
    'pattern' => 'lendmanagement/loan/create',
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
        return Loan::create(get());
    }
];
