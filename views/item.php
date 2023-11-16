<?php

use MediumSans\LendManagement\Category;
use MediumSans\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/item/(:any)',
    'action'  => function ($id) {

        $item = Item::find($id)[0];
        $categories = Category::getOptions();

        return [
            'component' => 'k-item-view',
            'extends' => 'k-form',
            'breadcrumb' => [
                [
                    'label' => 'Inventaire',
                    'link'  => 'lendmanagement/inventory'
                ],
                [
                    'label' => $item->name,
                    'link'  => 'lendmanagement/inventory/item/' . $id
                ]
            ],
            'props' => [
                'item'          => $item,
                'categories'    => $categories,
            ],
        ];
    }
];
