<?php

use Kirby\LendManagement\Loan;
use Kirby\LendManagement\Category;
use Kirby\LendManagement\Item;

return [
    'pattern' => 'lendmanagement',
    'action'  => function () {
        return [
            'component' => 'k-dashboard-view',
            'props' => [
                'items' => Item::list(),
                'stats' => [
                    [
                        'label' => t('view.dashboard.stats.inprogress'),
                        'value' => Loan::totalPendingLoans(),
                        'info' => ''
                    ],
                    [
                        'label' => t('view.dashboard.stats.delayed'),
                        'value' => Loan::totalLatePendingLoans(),
                        'info' => ''
                    ],
                    [
                        'label' => t('view.dashboard.stats.prolonged'),
                        'value' => Loan::totalPendingAndProlongedLoans(),
                        'info' => ''
                    ],
                ],
                'loans' => Loan::collection(),
            ]
        ];
    }
];
