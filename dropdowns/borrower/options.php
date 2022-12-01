<?php

return [
    'pattern' => 'lendmanagement/borrower/(:any)',
    'action'  => function (string $id) {
        return [
            [
                'text'   => 'Edit',
                'icon'   => 'edit',
                'dialog' => 'lendmanagement/borrower/' . $id . '/update'
            ],
            [
                'text'   => 'Delete',
                'icon'   => 'trash',
                'dialog' => 'lendmanagement/borrower/' . $id . '/delete'
            ]
        ];
    }
];
