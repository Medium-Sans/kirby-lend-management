<?php

use Kirby\LendManagement\Category;
use Kirby\Toolkit\A;

return [
    'title' => [
        'label' => 'Title',
        'type' => 'text'
    ],
    'description' => [
        'label' => 'Description',
        'type' => 'textarea',
        'buttons' => false
    ],
    'categoryId' => [
        'label' => 'Catégorie',
        'type' => 'select',
        'empty' => false,
        'width' => '1/2',
        'options' => Category::getOptions(),
    ],
    'quantity' => [
        'label' => 'Quantité',
        'type' => 'number',
        'step' => 1,
        'default' => 1,
        'width' => '1/2'
    ],
    'notes' => [
        'label' => 'Notes',
        'type' => 'textarea'
    ],
];
