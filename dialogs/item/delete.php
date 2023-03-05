<?php

use MediumSans\LendManagement\Item;
use Kirby\Panel\Panel;

return [
    'pattern' => 'inventory/item/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => t('lendmanagement.item.delete')
            ]
        ];
    },
    'submit' => function (string $id): void {
        Item::delete($id);
        Panel::go('lendmanagement/inventory');
    }
];
