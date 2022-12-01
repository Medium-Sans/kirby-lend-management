<?php

use Kirby\LendManagement\Borrower;

return [
    'pattern' => 'lendmanagement/borrower/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Do you really want to delete this student ?'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Borrower::delete($id);
    }
];
