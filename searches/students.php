<?php

use MediumSans\LendManagement\Borrower;
use Kirby\Toolkit\Str;

return [
    'label' => 'Emprunteur',
    'icon'  => 'tag',
    'query' => function (string $query) {
        $borrowers = Borrower::list();
        $results  = [];
        foreach ($borrowers as $borrower) {
            if ((Str::contains($borrower['firstname'], $query, true) ||
                Str::contains($borrower['lastname'], $query, true) ||
                Str::contains($borrower['email'], $query, true))
                === true) {
                $results[] = [
                    'text' => $borrower['title'],
                    'link' => '/borrowers',
                    'image' => [
                        'icon' => 'tag',
                        'back' => 'yellow-400'
                    ]
                ];
            }
        }

        return $results;
    }
];
