<?php

use Kirby\LendManagement\Loan;

return [
    'pattern' => 'lendmanagement/loan/(:any)/extend',
    'load'    => function (string $id) {
        $loan = Loan::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => [
                    'extended' => [
                        'label' => 'days',
                        'type' => 'number',
                        'steps' => '7'
                    ]
                ],
                'value'  => $loan,
                'size'   => 'large'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Loan::extend($id, get());
    }
];
