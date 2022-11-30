<?php

use Kirby\LendManagement\Item;
use Kirby\Toolkit\Str;

return [
    'label' => 'Objets',
    'icon'  => 'tag',
    'query' => function (string $query) {
        $items = Item::list();
        $results  = [];
        foreach ($items as $item) {
            if (Str::contains($item['title'], $query, true) === true) {
                $results[] = [
                    'text' => $item['title'],
                    'link' => '/items',
                    'image' => [
                        'icon' => 'tag',
                        'back' => 'yellow-400'
                    ]
                ];
            }
        }

        return $results;
    }
];
