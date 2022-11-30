<?php

use Kirby\LendManagement\Borrower;

return [
    'pattern' => 'lendmanagement/borrowers',
    'action'  => function () {
        return [
            'component' => 'k-borrowers-view',
            'breadcrumb' => [
                [
                    'label' => t('view.borrowers.breadcrumb'),
                    'link'  => 'lendmanagement/borrowers'
                ]
            ],
            'props' => [
                'borrowers' => Borrower::list(),
            ]
        ];
    }
];
