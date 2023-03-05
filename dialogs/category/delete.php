<?php

use MediumSans\LendManagement\Category;
use Kirby\Panel\Panel;

return [
    'pattern' => 'inventory/category/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => t('lendmanagement.category.delete')
            ]
        ];
    },
    'submit' => function (string $id): void {
        Category::delete($id);
        Panel::go('lendmanagement/inventory');
    }
];
