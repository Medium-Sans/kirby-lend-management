<?php

use Kirby\LendManagement\Category;
use Kirby\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/inventory/item/(:any)',
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
                    'label' => $item->title,
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
