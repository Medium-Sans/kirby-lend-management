<?php

use Kirby\Http\Header;
use MediumSans\LendManagement\Borrower;
use MediumSans\LendManagement\Item;
use Kirby\Toolkit\A;

return [
    'startDate' => [
        'label' => 'Date de début',
        'type' => 'date',
        'time' => false,
        'required' => true,
        'width' => '1/2'
    ],
    'endDate' => [
        'label' => 'Date de fin',
        'type' => 'date',
        'time' => false,
        'required' => true,
        'width' => '1/2'
    ],
    'borrowerId' => [
        'label' => 'Emprunteur.se.x',
        'type' => 'multiselect',
        'max' => 1,
        'search' => true,
        'required' => true,
        'options' => Borrower::getOptions(),
    ],
    'itemIds' => [
        'label' => 'Objets',
        'type' => 'multiselect',
        'search' => true,
        'required' => true,
        'options' => Item::getOptions(),
    ]
];
