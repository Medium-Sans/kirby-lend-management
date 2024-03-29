<?php

use MediumSans\LendManagement\Category;

return [
    'pattern' => 'lendmanagement/category/(:any)/update',
    'load'    => function (string $id) {
        $category = Category::find($id)[0];

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value'  => $category,
                'size'   => 'large'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Category::update($id, get());
    }
];
