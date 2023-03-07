<?php

@include_once __DIR__ . '/vendor/autoload.php';

load([
    'MediumSans\LendManagement\Lend'          => __DIR__ . '/classes/Lend.php',
    'MediumSans\LendManagement\LendItems'     => __DIR__ . '/classes/LendItems.php',
    'MediumSans\LendManagement\LendExtension' => __DIR__ . '/classes/LendExtension.php',
    'MediumSans\LendManagement\Category'      => __DIR__ . '/classes/Category.php',
    'MediumSans\LendManagement\Item'          => __DIR__ . '/classes/Item.php',
    'MediumSans\LendManagement\Borrower'      => __DIR__ . '/classes/Borrower.php',
    'MediumSans\LendManagement\Database'      => __DIR__ . '/classes/Database.php',
    'MediumSans\LendManagement\Mailer'        => __DIR__ . '/classes/Mailer.php',
]);

Kirby::plugin('mediumsans/kirby-lend-management', [
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
                // Lend
                require __DIR__ . '/dialogs/lend/create.php',
                require __DIR__ . '/dialogs/lend/delete.php',
                require __DIR__ . '/dialogs/lend/update.php',
                require __DIR__ . '/dialogs/lend/extend.php',
                require __DIR__ . '/dialogs/lend/sendMessage.php',
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
                require __DIR__ . '/views/lend.php',
                require __DIR__ . '/views/lendAdd.php',
            ]
        ]
    ],
    'api' => require __DIR__ . '/routes/index.php',
    'translations' => require __DIR__ . '/i18n/i18n.php',
    'beebmx.kirby-db.drivers' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => __DIR__ . '/database/db.sqlite',
        ]
    ],
]);
