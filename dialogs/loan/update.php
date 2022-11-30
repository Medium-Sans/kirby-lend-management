<?php

use Kirby\LendManagement\Loan;
use Kirby\Toolkit\A;

return [
    'pattern' => 'lendmanagement/loan/(:any)/update',
    'load'    => function (string $id) {
        $loan = Loan::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value'  => $loan,
                'size'   => 'large'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Loan::update($id, get());
    }
];
