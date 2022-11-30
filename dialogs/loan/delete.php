<?php

use Kirby\LendManagement\Loan;

return [
    'pattern' => 'workshop/category/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Do you really want to delete this loan ?'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Loan::delete($id);
    }
];
