<?php

use Kirby\LendManagement\Borrower;
use Kirby\LendManagement\Item;
use Kirby\LendManagement\Lend;
use Kirby\LendManagement\LendExtension;
use Kirby\LendManagement\LendItems;

return [
    'pattern' => 'lendmanagement/lend/(:any)',
    'action'  => function ($id) {

        $lend = Lend::find($id);
        $borrower = Borrower::find($lend->borrower_id);

        $lend_items = LendItems::getItemsbyLend($id);
        $items = Item::getItemsByIds($lend_items);
        $startDate = date_format(date_create($lend->start_date), 'd.m.Y');
        $endDate = date_format(date_create($lend->end_date), 'd.m.Y');

        $extensions = LendExtension::getExtensions($id);
        $expiryDate =  date_format(date_create(LendExtension::getLendExpiryDateByLendId($endDate, $id)), 'Y-m-d');
        $nbrOfDaysAdded = LendExtension::getNumbersofDayAddedInTotalByLendId($id);

        return [
            'component' => 'k-lend-view',
            'breadcrumb' => [
                [
                    'label' => $borrower->firstname . ' - ' . $startDate . ' • ' . $endDate,
                    'link'  => '/lendmanagement/lend/' . $id
                ]
            ],
            'props' => [
                'lend'              => $lend,
                'borrower'          => $borrower,
                'items'             => $items,
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'extensions'        => $extensions,
                'expiry_date'       => $expiryDate,
                'nbr_of_days_added' => $nbrOfDaysAdded,
            ]
        ];
    }
];
