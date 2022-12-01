<?php

return [
    'pattern' => 'lendmanagement/inventory/item/(:any)',
    'action'  => function (string $id) {
        return [
            [
                'text'   => 'Edit',
                'icon'   => 'edit',
                'dialog' => 'lendmanagement/inventory/item/' . $id . '/update'
            ],
            [
                'text'   => 'Delete',
                'icon'   => 'trash',
                'dialog' => 'lendmanagement/inventory/item/' . $id . '/delete'
            ]
        ];
    }
];
