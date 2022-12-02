<?php

use Kirby\LendManagement\Item;
use Kirby\Panel\Panel;

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
        Item::delete($id);
        Panel::go('lendmanagement/inventory');
        return Item::delete($id);
    }
];
