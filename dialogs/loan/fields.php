<?php

use Kirby\Http\Header;
use Kirby\LendManagement\Borrower;
use Kirby\LendManagement\Item;
use Kirby\Toolkit\A;

return [
    'startDate' => [
        'label' => 'Date de début',
        'type' => 'date',
        'time' => false,
        'width' => '1/2'
    ],
    'endDate' => [
        'label' => 'Date de fin',
        'type' => 'date',
        'time' => false,
        'width' => '1/2'
    ],
    'borrowerId' => [
        'label' => 'Emprunteur.se.x',
        'type' => 'multiselect',
        'search' => true,
        'options' => Borrower::getOptions(),
    ],
    'itemIds' => [
        'label' => 'Objets',
        'type' => 'multiselect',
        'search' => true,
        'options' => Item::getOptions(),
    ]
];
