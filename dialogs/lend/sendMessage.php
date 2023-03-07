<?php

use MediumSans\LendManagement\Borrower;
use MediumSans\LendManagement\Lend;
use MediumSans\LendManagement\Mailer;

use Kirby\Toolkit\I18n;

return [
    'pattern' => 'lendmanagement/lend/(:any)/sendMessage',
    'load'    => function (string $id) {
        $lend = Lend::find($id);

        return [
            'component' => 'k-form-dialog',
            'props' => [
                'fields' => [
                    'subject' => [
                        'label' => i18n::translate('lendmanagement.lend.sendMessage.subject'),
                        'type' => 'text'
                    ],
                    'body' => [
                        'label' => i18n::translate('lendmanagement.lend.sendMessage.body'),
                        'type' => 'textarea',
                        'size' => 'large'
                    ]
                ],
                'value'  => $lend,
                'size'   => 'large'
            ]
        ];
    },
    'submit' => function (string $id) {
        $data = get();
        $lend = Lend::find($data->id);
        $borrower= Borrower::find($lend->borrower_id);
        return Mailer::sendMessage($borrower->email, $data['subject'], $data['body']);
    }
];
