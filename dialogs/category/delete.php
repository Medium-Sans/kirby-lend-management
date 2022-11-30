<?php

use Kirby\LendManagement\Category;

return [
    'pattern' => 'inventory/category/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Do you really want to delete this category ?'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Category::delete($id);
    }
];
