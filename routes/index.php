<?php

use Kirby\Http\Response;
use MediumSans\LendManagement\Item;
use MediumSans\LendManagement\Lend;
use MediumSans\LendManagement\Mailer;

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
                'pattern' => 'lendmanagement/lend/create',
                'method' => 'POST',
                'action' => function () {
                    return Lend::create(get());
                }
            ],
            [
                'pattern' => 'lendmanagement/lend/(:any)/return',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Lend::return($id);
                }
            ],
            [
                'pattern' => 'lendmanagement/lend/(:any)/extend',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Lend::return($id);
                }
            ],
            [
                'pattern' => 'lendmanagement/lend/(:any)/notify',
                'method' => 'POST',
                'action' => function (string $id) {
                    $sent = Mailer::notifyBorrowerOfExpiration(Lend::find($id));
                    return response::json([ 'sent' => $sent ]);
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
