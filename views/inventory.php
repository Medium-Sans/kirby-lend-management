<?php

use MediumSans\LendManagement\Category;
use MediumSans\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/inventory',
    'action'  => function () {
        return [
            'component' => 'k-inventory-view',
            'props' => [
                'items' => Item::listWithCategory(),
                'categories' => Category::collection(),
            ]
        ];
    }
];
