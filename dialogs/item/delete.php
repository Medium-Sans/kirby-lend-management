<?php

use MediumSans\LendManagement\Item;
use Kirby\Panel\Panel;

return [
    'pattern' => 'lendmanagement/item/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => t('lendmanagement.item.delete')
            ]
        ];
    },
    'submit' => function (string $id) {
        return Item::delete($id);
    }
];
