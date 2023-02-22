<?php

use Kirby\Http\Response;
use Kirby\LendManagement\Item;
use Kirby\LendManagement\Loan;
use Kirby\Data\Data;

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
            ],
            [
                'pattern' => 'lendmanagement/loan/(:any)/return',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Loan::return($id);
                }
            ],
            [
                'pattern' => 'lendmanagement/loan/(:any)/extend',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Loan::return($id);
                }
            ],
            [
                'pattern' => 'lendmanagement/loan/(:any)/notify',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Loan::return($id);
                }
            ],
            [
                'pattern' => 'lendmanagement/item/(:any)/print',
                'method' => 'POST',
                'action' => function (string $id) {
                    $label = Item::getLabelFromItemId($id);
                    $encoded = base64_encode(trim($label));
                    return response::json([ 'data' => $encoded ]);
                }
            ],
        ];
    }
];
