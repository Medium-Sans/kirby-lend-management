<?php

use Kirby\LendManagement\Category;
use Kirby\Toolkit\Str;

return [
    'label' => 'Catégories',
    'icon'  => 'tag',
    'query' => function (string $query) {
        $categories = Category::list();
        $results  = [];
        foreach ($categories as $category) {
            if (Str::contains($category['title'], $query, true) === true) {
                $results[] = [
                    'text' => $category['title'],
                    'link' => '/categories',
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
