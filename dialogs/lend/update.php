<?php

use MediumSans\LendManagement\Lend;
use Kirby\Toolkit\A;

return [
    'pattern' => 'lendmanagement/lend/(:any)/update',
    'load'    => function (string $id) {
        $lend = Lend::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => require __DIR__ . '/fields.php',
                'value'  => $lend,
                'size'   => 'large'
            ]
        ];
    },
    'submit' => function (string $id) {
        return Lend::update($id, get());
    }
];
