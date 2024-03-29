<?php

use MediumSans\LendManagement\Borrower;

return [
    'pattern' => 'lendmanagement/borrower/create',
    'load' => function () {
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
        return Borrower::create(get());
    }
];
