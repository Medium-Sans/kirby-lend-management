<?php

use Kirby\LendManagement\Loan;
use Kirby\LendManagement\Borrower;

return [
    'label' => 'Emprunts',
    'icon'  => 'box',
    'query' => function (string $query) {
        $loans = Loan::list();
        $results  = [];
        foreach ($loans as $loan) {
            if ($loan['isReturned'] === true) {
                $results[] = [
                    'text' => Borrower::find($loan['studentId']) - $loan['stardDate'] . ' / ' . $loan['endDate'],
                    'link' => '/loans',
                    'image' => [
                        'icon' => 'box',
                        'back' => 'purple-400'
                    ]
                ];
            }
        }

        return $results;
    }
];
