<?php

use Kirby\LendManagement\Borrower;
use Kirby\LendManagement\Item;
use Kirby\LendManagement\Loan;
use Kirby\LendManagement\LoanItems;

return [
    'pattern' => 'lendmanagement/loan/(:any)',
    'action'  => function ($id) {

        $loan = Loan::find($id);
        $borrower = Borrower::find($loan['borrower_id']);
        $loan_items = LoanItems::getItemsbyLoan($id);
        $items = Item::getItemsByIds($loan_items);
        $startDate = date_format(date_create($loan['start_date']), 'd.m.Y');
        $endDate = date_format(date_create($loan['end_date']), 'd.m.Y');

        return [
            'component' => 'k-loan-view',
            'breadcrumb' => [
                [
                    'label' => $borrower->firstname . ' - ' . $startDate . ' • ' . $endDate,
                    'link'  => '/lendmanagement/loan/' . $id
                ]
            ],
            'props' => [
                'loan'      => $loan,
                'borrower'  => $borrower,
                'items'     => $items,
                'startDate' => $startDate,
                'endDate'   => $endDate,
            ]
        ];
    }
];
