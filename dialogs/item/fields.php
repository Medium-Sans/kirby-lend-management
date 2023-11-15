<?php

use MediumSans\LendManagement\Category;

return [
    'name' => [
        'label' => t('lendmanagement.dialogs.name'),
        'type' => 'text'
    ],
    'description' => [
        'label' => t('lendmanagement.dialogs.description'),
        'type' => 'textarea',
        'buttons' => false
    ],
    'category_id' => [
        'label' => t('lendmanagement.dialogs.category'),
        'type' => 'select',
        'empty' => false,
        'width' => '1/2',
        'options' => Category::getOptions(),
    ],
    'quantity' => [
        'label' => t('lendmanagement.dialogs.quantity'),
        'type' => 'number',
        'step' => 1,
        'default' => 1,
        'width' => '1/2'
    ],
    'notes' => [
        'label' => t('lendmanagement.dialogs.notes'),
        'type' => 'textarea'
    ],
];
