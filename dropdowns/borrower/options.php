<?php

return [
    'pattern' => 'lendmanagement/borrower/(:any)',
    'action'  => function (string $id) {
        return [
            [
                'text'   => t('lendmanagement.item.dropdown.edit'),
                'icon'   => 'edit',
                'dialog' => 'lendmanagement/borrower/' . $id . '/update'
            ],
            [
                'text'   => t('lendmanagement.item.dropdown.delete'),
                'icon'   => 'trash',
                'dialog' => 'lendmanagement/borrower/' . $id . '/delete'
            ]
        ];
    }
];
