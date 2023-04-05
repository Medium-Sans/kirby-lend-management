<?php

use MediumSans\LendManagement\Category;
use MediumSans\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/inventory/category/(:any)',
    'action'  => function ($id) {

        $category = Category::find($id)[0];

        return [
            'component' => 'k-category-view',
            'breadcrumb' => [
                [
                    'label' => 'Inventaire',
                    'link'  => 'lendmanagement/inventory'
                ],
                [
                    'label' => $category->name,
                    'link'  => '/lendmanagement/inventory/category/' . $id
                ]
            ],
            'props' => [
                'category' => $category,
                'items' => Item::getItemsByCategory($category->id),
            ]
        ];
    }
];
