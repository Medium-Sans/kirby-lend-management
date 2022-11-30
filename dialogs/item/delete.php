<?php

use Kirby\LendManagement\Item;

return [
    'pattern' => 'inventory/item/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Do you really want to delete this item ?'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Item::delete($id);
    }
];
