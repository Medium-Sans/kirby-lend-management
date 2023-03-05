<?php

use MediumSans\LendManagement\Lend;
use MediumSans\LendManagement\Borrower;

return [
    'label' => 'Emprunts',
    'icon'  => 'box',
    'query' => function (string $query) {
        $lends = Lend::list();
        $results  = [];
        foreach ($lends as $lend) {
            if ($lend['isReturned'] === true) {
                $results[] = [
                    'text' => Borrower::find($lend['studentId']) - $lend['stardDate'] . ' / ' . $lend['endDate'],
                    'link' => '/lends',
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
