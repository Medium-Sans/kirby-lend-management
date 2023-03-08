<?php

use MediumSans\LendManagement\Lend;
use MediumSans\LendManagement\LendExtension;
use Kirby\Toolkit\I18n;
use MediumSans\LendManagement\Mailer;

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
        LendExtension::create($id, $data['extended']);
        return Mailer::notifyBorrowerOfExtension(Lend::find($id));
    }
];
