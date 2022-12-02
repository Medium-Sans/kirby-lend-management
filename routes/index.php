<?php

use Kirby\LendManagement\Item;
use Kirby\Panel\Panel;

return [
    'routes' => function ($kirby) {
        return [
            [
                'pattern' => 'lendmanagement/item/(:any)/update',
                'method' => 'POST',
                'action' => function (string $id) {
                    return Item::update($id, get());
                }
            ],
            [
                'pattern' => 'lendmanagement/item/(:any)/delete',
                'method' => 'GET',
                'action' => function (string $id) use ($kirby) {
                    return Item::delete($id);
                }
            ]
        ];
    }
];
