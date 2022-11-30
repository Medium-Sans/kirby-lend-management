<?php

use Kirby\LendManagement\Item;
use Kirby\Toolkit\A;

return [
    'pattern' => 'inventory/item/(:any)/update',
    'load'    => function (string $id) {
        $item = Item::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value'  => $item
            ]
        ];
    },
    'submit' => function (string $id) {
        return Item::update($id, get());
    }
];
