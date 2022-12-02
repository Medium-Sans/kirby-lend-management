<?php

load([
    'Kirby\LendManagement\Loan'     => __DIR__ . '/classes/Loan.php',
    'Kirby\LendManagement\Category' => __DIR__ . '/classes/Category.php',
    'Kirby\LendManagement\Item'     => __DIR__ . '/classes/Item.php',
    'Kirby\LendManagement\Borrower' => __DIR__ . '/classes/Borrower.php',
]);

Kirby::plugin('scardoso/lendmanagement', [
    'areas' => [
        'lendmanagement' => [
            'label' => t([
                'en' => 'Lend Management',
                'fr' => 'Gestion des prêts',
            ]),
            'icon'  => 'cart',
            'menu'  => true,
            'view'  => 'k-dashboard-view',
            'dialogs' => [
                // Category
                require __DIR__ . '/dialogs/category/create.php',
                require __DIR__ . '/dialogs/category/delete.php',
                require __DIR__ . '/dialogs/category/update.php',
                // Loan
                require __DIR__ . '/dialogs/loan/create.php',
                require __DIR__ . '/dialogs/loan/delete.php',
                require __DIR__ . '/dialogs/loan/update.php',
                // Borrower
                require __DIR__ . '/dialogs/borrower/create.php',
                require __DIR__ . '/dialogs/borrower/delete.php',
                require __DIR__ . '/dialogs/borrower/update.php',
                // Item
                require __DIR__ . '/dialogs/item/create.php',
                require __DIR__ . '/dialogs/item/delete.php',
                require __DIR__ . '/dialogs/item/update.php',
            ],
            'dropdowns' => [
                require __DIR__ . '/dropdowns/borrower/options.php',
                require __DIR__ . '/dropdowns/item/options.php',
            ],
            'views' => [
                require __DIR__ . '/views/dashboard.php',
                require __DIR__ . '/views/inventory.php',
                require __DIR__ . '/views/borrowers.php',
                require __DIR__ . '/views/category.php',
                require __DIR__ . '/views/item.php',
                require __DIR__ . '/views/loan.php',
                require __DIR__ . '/views/loanAdd.php',
            ]
        ]
    ],
    'api' => require __DIR__ . '/routes/index.php',
    'translations' => require __DIR__ . '/i18n/i18n.php',
]);
