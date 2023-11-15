<?php

use MediumSans\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/item/(:any)/update',
    'load'    => function (string $id) {
        $item = Item::find($id)[0];

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value'  => $item,
                'size'   => 'large',
            ]
        ];
    },
    'submit' => function (string $id) {
        return Item::update($id, get());
    }
];
