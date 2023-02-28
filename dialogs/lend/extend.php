<?php

use Kirby\LendManagement\Lend;
use Kirby\LendManagement\LendExtension;
use Kirby\Toolkit\I18n;

return [
    'pattern' => 'lendmanagement/lend/(:any)/extend',
    'load'    => function (string $id) {
        $lend = Lend::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => [
                    'extended' => [
                        'label' => i18n::translate('lendmanagement.lend.extensions.nbrOfDays'),
                        'type' => 'number',
                        'min' => 7,
                        'steps' => '7'
                    ]
                ],
                'value'  => $lend,
                'size'   => 'small'
            ]
        ];
    },
    'submit' => function (string $id) {
        $data = get();
        return LendExtension::create($id, $data['extended']);
    }
];
