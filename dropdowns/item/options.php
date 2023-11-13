<?php

return [
    'pattern' => 'lendmanagement/item/(:any)',
    'action'  => function (string $id) {
        return [
            [
                'text'   => t('lendmangement.item.dropdown.preview'),
                'icon'   => 'preview',
                'link'   => '/lendmanagement/inventory/item/' . $id
            ],
            [
                'text'   => t('lendmangement.item.dropdown.edit'),
                'icon'   => 'edit',
                'dialog' => 'inventory/item/' . $id . '/update'
            ],
            [
                'text'   => t('lendmangement.item.dropdown.delete'),
                'icon'   => 'trash',
                'dialog' => 'lendmanagement/inventory/item/' . $id . '/delete'
            ]
        ];
    }
];
