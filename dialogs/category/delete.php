<?php

use MediumSans\LendManagement\Category;
use Kirby\Panel\Panel;

return [
    'pattern' => 'lendmanagement/category/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => t('lendmanagement.category.delete')
            ]
        ];
    },
    'submit' => function (string $id) {
        return Category::delete($id);
    }
];
