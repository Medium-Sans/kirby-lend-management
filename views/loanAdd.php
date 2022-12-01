<?php

use Kirby\LendManagement\Borrower;
use Kirby\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/loan-add',
    'action'  => function () {
        $startDate = date_create('now', new DateTimeZone('Europe/Paris'))->format('Y-m-d');
        $endDate = date_create('now', new DateTimeZone('Europe/Paris'))->modify('+7 days')->format('Y-m-d');

        return [
            'component' => 'k-loan-add-view',
            'breadcrumb' => [
                [
                    'label' => t('view.loan.add'),
                    'link'  => 'lendmanagement/borrowers'
                ]
            ],
            'props' => [
                'borrowers'  => Borrower::getOptions(),
                'items'     => Item::getOptions(),
                'startDate' => $startDate,
                'endDate'  => $endDate,
            ]
        ];
    }
];
