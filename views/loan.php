<?php

use Kirby\LendManagement\Borrower;
use Kirby\LendManagement\Item;
use Kirby\LendManagement\Loan;

return [
    'pattern' => 'lendmanagement/loan/(:any)',
    'action'  => function ($id) {

        $loan = Loan::find($id);
        $borrower = Borrower::find($loan['borrowerId'][0]);
        $items = Item::getItemsByIds($loan['itemIds']);
        $startDate = date_format(date_create($loan['startDate']), 'd.m.Y');
        $endDate = date_format(date_create($loan['endDate']), 'd.m.Y');

        return [
            'component' => 'k-loan-view',
            'breadcrumb' => [
                [
                    'label' => $borrower['firstname'] . ' - ' . $startDate . ' • ' . $endDate,
                    'link'  => '/lendmanagement/loan/' . $id
                ]
            ],
            'props' => [
                'loan'      => $loan,
                'borrower'  => $borrower,
                'items'     => $items,
            ]
        ];
    }
];
