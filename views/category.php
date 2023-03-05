<?php

use MediumSans\LendManagement\Category;
use MediumSans\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/inventory/category/(:any)',
    'action'  => function ($id) {

        $category = Category::find($id);

        return [
            'component' => 'k-category-view',
            'breadcrumb' => [
                [
                    'label' => 'Inventaire',
                    'link'  => 'lendmanagement/inventory'
                ],
                [
                    'label' => $category['title'],
                    'link'  => '/lendmanagement/inventory/category/' . $id
                ]
            ],
            'props' => [
                'category' => $category,
                'items' => Item::getItemsByCategory($category['id']),
            ]
        ];
    }
];
