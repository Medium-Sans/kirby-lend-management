<?php

use Kirby\LendManagement\Item;
use Kirby\LendManagement\Loan;

return [
    'routes' => function () {
        return [
            [
                'pattern' => 'lendmanagement/item/(:any)/update',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Item::update($id, get());
                }
            ],
            [
                'pattern' => 'lendmanagement/loan/create',
                'method' => 'POST',
                'action' => function () {
                    return Loan::create(get());
                }
            ]
        ];
    }
];
