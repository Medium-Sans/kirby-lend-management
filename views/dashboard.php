<?php

use Kirby\LendManagement\Lend;
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
                        'value' => Lend::totalPendingLends(),
                        'info' => ''
                    ],
                    [
                        'label' => t('view.dashboard.stats.delayed'),
                        'value' => Lend::totalLatePendingLends(),
                        'info' => ''
                    ],
                    [
                        'label' => t('view.dashboard.stats.prolonged'),
                        'value' => Lend::totalPendingAndProlongedLends(),
                        'info' => ''
                    ],
                ],
                'lends' => Lend::getCurrentLends(),
                'late_lends' => Lend::getLateLends(),
                'returned_lends' => Lend::getReturnedLends(),
            ]
        ];
    }
];
