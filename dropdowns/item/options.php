<?php

return [
    'pattern' => 'lendmanagement/item/(:any)',
    'action'  => function (string $id) {
        return [
            [
                'text'   => t('lendmanagement.item.dropdown.preview'),
                'icon'   => 'preview',
                'link' => '/lendmanagement/item/' . $id
            ],
            [
                'text'   => t('lendmanagement.item.dropdown.edit'),
                'icon'   => 'edit',
                'dialog' => 'lendmanagement/item/' . $id . '/update'
            ],
            [
                'text'   => t('lendmanagement.item.dropdown.delete'),
                'icon'   => 'trash',
                'dialog' => 'lendmanagement/item/' . $id . '/delete'
            ]
        ];
    }
];
