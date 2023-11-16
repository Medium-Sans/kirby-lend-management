<?php

use Kirby\Toolkit\I18n;
use MediumSans\LendManagement\Lend;
use MediumSans\LendManagement\LendExtension;
use MediumSans\LendManagement\Mailer;

return [
    'pattern' => 'lendmanagement/lend/(:any)/extend',
    'load'    => function (string $id) {
        $lend = Lend::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => [
                    'notify' => [
                        'label' => i18n::translate('lendmanamement.lend.extend.notifyBorrower'),
                        'type' => 'toggle',
                        'text' => [
                            i18n::translate('lendmanagement.lend.extend.notifyBorrower.no'),
                            i18n::translate('lendmanagement.lend.extend.notifyBorrower.yes')
                        ]
                    ],
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

        if(array_key_exists('notify', $data)) {
            if($data['notify']) {
                Mailer::notifyBorrowerOfExtension(Lend::find($id));
            }
        }

        return true;
    }
];
