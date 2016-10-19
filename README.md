Serviform
=========

[![Latest Stable Version](https://poser.pugx.org/marvin255/serviform/v/stable.png)](https://packagist.org/packages/marvin255/serviform)
[![Total Downloads](https://poser.pugx.org/marvin255/serviform/downloads.png)](https://packagist.org/packages/marvin255/serviform)
[![License](https://poser.pugx.org/marvin255/serviform/license.svg)](https://packagist.org/packages/marvin255/serviform)
[![Build Status](https://travis-ci.org/marvin255/serviform.svg?branch=master)](https://travis-ci.org/marvin255/serviform)

Form constructor for php.



Installation
------------

**Via [Composer](https://getcomposer.org/doc/00-intro.md)**

Add code below into your composer.json in section `require`:

```javascript
"require": {
    "marvin255/serviform": "*"
}
```

**Common**

Download library archive and extract to project. Make sure that `Autoloader.php` is included.

```php
require_once 'lib/Autoloader.php';
```



Basic usage
-----------

Use `\serviform\helpers\FactoryFields` to create form. For each element you must specify an array with required `type` key or set an object that implements `\serviform\IElement` interface. Same is for rules. For each rule you must specify an array with required `type` key or set an object that implements `\serviform\IValidator`.

```php
$form = \serviform\helpers\FactoryFields::init([
    'type' => 'form',
    'name' => 'form',
    'elements' => [
        'name' => [
            'label' => 'Name',
            'type' => 'input',
            'attributes' => [
                'class' => 'class',
                'data-attribute' => 'some attribute',
            ],
        ],
        'email' => [
            'label' => 'Email',
            'type' => 'input',
        ],
        'message' => [
            'label' => 'Message',
            'type' => 'textarea',
        ],
        'next' => [
            'type' => 'button',
            'label' => 'Send',
        ],
    ],
    'rules' => [
        [['text_1', 'email', 'message'], 'required'],
        [['email'], 'regexp', 'regexp' => 'email'],
    ],
]);
```

Load data to form. Validate form fields. And if all checks are passed do some action.

```php
if ($form->loadData() && $form->validate()) {
    //here is some action if form's data is valid, e.g. mail() or redirect
}
```

Render form.

```php
echo $form;
```
