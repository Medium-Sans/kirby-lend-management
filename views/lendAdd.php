<?php

use MediumSans\LendManagement\Borrower;
use MediumSans\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/lend-add',
    'action'  => function () {
        $startDate = date_create('now', new DateTimeZone('Europe/Paris'))->format('Y-m-d');
        $endDate = date_create('now', new DateTimeZone('Europe/Paris'))->modify('+7 days')->format('Y-m-d');

        return [
            'component' => 'k-lend-add-view',
            'extends' => 'k-model-view',
            'breadcrumb' => [
                [
                    'label' => t('view.lend.add'),
                    'link'  => 'lendmanagement/borrowers'
                ]
            ],
            'props' => [
                'borrower_id'  => Borrower::getOptions(),
                'item_ids'     => Item::getOptions(),
                'start_date' => $startDate,
                'end_date'  => $endDate,
                'items'     => Item::getOptions(),
            ]
        ];
    }
];
