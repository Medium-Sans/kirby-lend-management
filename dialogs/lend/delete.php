<?php

use MediumSans\LendManagement\Lend;

return [
    'pattern' => 'workshop/category/(:any)/delete',
    'load' => function () {
        return [
            'component' => 'k-remove-dialog',
            'props' => [
                'text' => 'Do you really want to delete this lend ?'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Lend::delete($id);
    }
];
