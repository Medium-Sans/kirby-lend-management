<?php

return [
    'pattern' => 'lendmanagement/category/(:any)',
    'action'  => function (string $id) {
        return [
            [
                'text'   => t('lendmangement.item.dropdown.edit'),
                'icon'   => 'edit',
                'dialog' => 'lendmanagement/category/' . $id . '/update'
            ],
            [
                'text'   => t('lendmangement.item.dropdown.delete'),
                'icon'   => 'trash',
                'dialog' => 'lendmanagement/category/' . $id . '/delete'
            ]
        ];
    }
];
