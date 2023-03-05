<?php

use MediumSans\LendManagement\Borrower;

return [
    'pattern' => 'lendmanagement/borrower/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => t('lendmanagement.borrower.delete')
            ]
        ];
    },
    'submit' => function (string $id) {
        return Borrower::delete($id);
    }
];
