# Kirby - Lend Management

Manage your lendings, notify the people you lend to and keep track of your items.

- Email notification
- QrCode Labels - Dymo Printer
- Webcam Scanner to add items faster to a loan
- Clear and quick overview of your items and lends
- Inventory management with categories and quantities, description, etc...

****

![dashboard.png](docs%2Fdashboard.png)

## Table of Contents
- [Installation](##installation)
- [Setup](##setup)
 - [Database](###database)
 - [Email Notifications](###email-notifications)
   - [Properties](####properties)
   - [Templates](####templates)
 - [Label Printing](###label-printing)
 - [Dependencies](##dependencies)
 - [License](##license)
 - [Credits](##credits)

## Installation

* Copy the plugin folder to your site/plugins folder
* Run `composer install` in the plugin folder

## Setup

### Database

We use a sqlite database to store the data. For it to work you need to set those parameters in your config.php file:

````php
return [
    ...
    'beebmx.kirby-db.default' => 'sqlite',
    'beebmx.kirby-db.drivers' => [
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => __DIR__ . '/../storage/database/lendmanagement.sqlite',
            # with this configuration the database will be created in a folder named "database" inside
            # the "storage" folder in the "site" folder that sits at the root of your kirby project.
            'prefix' => ''
        ]
    ],
];
````
And create the database file in the specified location, the database will then be created the first time you reach the dashboard.

### Email Notifications

For the notifications to work you need to setup a mail server. You can do this by adding the following to your config.php file:

```php
return [
    ...
    'email' => [
        'transport' => [
            'type' => 'smtp',
            'host' => 'my.hostname.com',
            'port' => 465, // 587
            'security' => true, // false if 587
            'auth' => true,
            'username' => 'my@username.com',
            'password' => 'mypassword'
        ]
    ]
];
```

#### Properties

You can use those properties to change the sender, replyTo and subject of the "expired" and "extended" emails.

```php
return [
    ...
    'mediumsans.kirby-lend-management.email.sender' => 'the-email@from',
    'mediumsans.kirby-lend-management.email.replyTo' => 'the-email@we-will-reply-to',
    'mediumsans.kirby-lend-management.email.subject.expired' => 'Subject | Of the expired notification',
    'mediumsans.kirby-lend-management.email.subject.extended' => 'Subject | Of the extended notification',
    ...
]
```

#### Templates

Emails templates are located in the `site/templates/emails` folder, you can change them to your liking.

### Label Printing

At the moment we only support the Dymo LabelWriter 450. *Templates are hardcoded not ideal, but I'm working on it.*

## Dependencies
- [chillerlan/php-qrcode](https://github.com/chillerlan/php-qrcode)
- [beebmx/kirby-db](https://github.com/beebmx/kirby-db)

## License

The "Kirby Lend Management" plugin is not free software. In order to run on a public server you must purchase a valid Kirby license and a valid "Kirby Lend Management" license.

## Credits

- [Samuel Cardoso](https://github.com/r3d2)
