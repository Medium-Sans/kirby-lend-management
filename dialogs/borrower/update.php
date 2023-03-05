<?php

use MediumSans\LendManagement\Borrower;

return [
    'pattern' => 'lendmanagement/borrower/(:any)/update',
    'load'    => function (string $id) {
        $borrower = Borrower::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value'  => $borrower,
                'size'   => 'large'
            ]
        ];
    },
    'submit' => function (string $id) {
        return (new MediumSans\LendManagement\Borrower)->update($id, get());
    }
];
