<?php

use Kirby\LendManagement\Category;
use Kirby\LendManagement\Item;

return [
    'pattern' => 'lendmanagement/inventory',
    'action'  => function () {
        return [
            'component' => 'k-inventory-view',
            'breadcrumb' => [
                [
                    'label' => 'Inventaire',
                    'link'  => 'lendmanagement/inventory'
                ]
            ],
            'props' => [
                'stats' => [
                    [
                        'label' => t('view.inventory.stats.objectslended'),
                        'value' => Item::getNumberOfItemsLended(),
                        'info' => ''
                    ],
                    [
                        'label' => t('view.inventory.stats.objectsinstock'),
                        'value' => Item::count(),
                        'info' => ''
                    ],
                    [
                        'label' => t('view.inventory.stats.nbrcategories'),
                        'value' => Category::count(),
                        'info' => ''
                    ],
                ],
                'items' => Item::collection(),
                'categories' => Category::collection(),
            ]
        ];
    }
];
