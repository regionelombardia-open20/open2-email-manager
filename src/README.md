# amos-email-manager

Extension to manage emails (templates, spool,..)

## Installation

### 1. Add module to your application

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
composer require open20/amos-email-manager
```

or add this row

```
"open20/amos-email-manager": "dev-master"
```

to the require section of your `composer.json` file.

### 2. Add module configuration

Add module to your main config in common like this:
	
```php
<?php
'modules' => [
    'email' => [
        'class' => 'open20\amos\emailmanager\AmosEmail',
        'templatePath' => '@common/mail/emails',
    ],
],
```

### 3. Apply migrations

To apply migrations you can launch this command:

```bash
php yii migrate/up --migrationPath=@vendor/open20/amos-email-manager/src/migrations
```

or add this row to your migrations config in console:

```php
<?php
return [
    '@vendor/open20/amos-email-manager/src/migrations',
];
```
