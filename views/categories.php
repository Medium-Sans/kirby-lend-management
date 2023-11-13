<?php

use MediumSans\LendManagement\Category;

return [
    'pattern' => 'lendmanagement/categories',
    'action'  => function () {
        return [
            'component' => 'k-categories-view',
            'props' => [
                'categories' => Category::list(),
            ]
        ];
    }
];
