<?php

use MediumSans\LendManagement\Borrower;

return [
    'pattern' => 'lendmanagement/borrowers',
    'action'  => function () {
        return [
            'component' => 'k-borrowers-view',
            'props' => [
                'borrowers' => Borrower::listWithLastLend(),
            ]
        ];
    }
];
