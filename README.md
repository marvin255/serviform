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

Use `\serviform\helpers\FactoryFields` to create form. For each element must be specified an array with required `type` key or set an object that implements `\serviform\IElement` interface. Same is for rules. For each rule must be specified an array with required `type` key or set an object that implements `\serviform\IValidator`.

```php
$form = \serviform\helpers\FactoryFields::init([
    'type' => 'form',
    'name' => 'feedback',
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
        'send' => [
            'label' => 'Send',
            'type' => 'button',
        ],
    ],
    'rules' => [
        [['name', 'email', 'message'], 'required'],
        [['email'], 'regexp', 'regexp' => 'email'],
    ],
]);
```

Load data to form. Validate form fields. If all checks are passed do some action.

```php
if ($form->loadData() && $form->validate()) {
    //get data form form
    $formData = $form->getValue();
    //here is some action if form's data is valid, e.g. mail() or redirect
}
```

Render form.

```php
echo $form;
```


Advanced usage
--------------

To create stepped form or insert one form to other form set new `form` element as an element of base form. You can insert any form to any other form with no nesting limit.

```php
$form = \serviform\helpers\FactoryFields::init([
    'type' => 'form',
    'name' => 'feedback',
    'elements' => [
        'message' => [
            'type' => 'form',
            'elements' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'input',
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'input',
                ],
                'message' => [
                    'label' => 'Message',
                    'type' => 'textarea',
                ],
            ],
            'rules' => [
                [['name', 'email', 'message'], 'required'],
                [['email'], 'regexp', 'regexp' => 'email'],
            ],
        ],
        'address' => [
            'type' => 'form',
            'elements' => [
                'country' => [
                    'label' => 'Country',
                    'type' => 'input',
                ],
                'city' => [
                    'label' => 'City',
                    'type' => 'input',
                ],
                'street' => [
                    'label' => 'Street',
                    'type' => 'input',
                ],
            ],
            'rules' => [
                [['country', 'city', 'street'], 'required'],
            ],
        ],
        'send' => [
            'type' => 'button',
            'label' => 'Send',
        ],
    ],
]);

if ($form->loadData() && $form->validate()) {
    //get data form form
    $formData = $form->getValue();
    //here is some action if form's data is valid, e.g. mail() or redirect
}

echo $form;
```

To create form with duplicated fields there is no need to duplicate all their descriptions. Just use `multiple` field type.

```php
$form = \serviform\helpers\FactoryFields::init([
    'type' => 'form',
    'name' => 'feedback',
    'elements' => [
        'message' => [
            'type' => 'form',
            'elements' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'input',
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'input',
                ],
                'message' => [
                    'label' => 'Message',
                    'type' => 'textarea',
                ],
            ],
            'rules' => [
                [['name', 'email', 'message'], 'required'],
                [['email'], 'regexp', 'regexp' => 'email'],
            ],
        ],
        'address' => [
            'type' => 'multiple',
            'min' => 3,
            'max' => 3,
            'multiplier' => [
                'type' => 'form',
                'elements' => [
                    'country' => [
                        'label' => 'Country',
                        'type' => 'input',
                    ],
                    'city' => [
                        'label' => 'City',
                        'type' => 'input',
                    ],
                    'street' => [
                        'label' => 'Street',
                        'type' => 'input',
                    ],
                ],
                'rules' => [
                    [['country', 'city', 'street'], 'required'],
                ],
            ],
        ],
        'send' => [
            'type' => 'button',
            'label' => 'Send',
        ],
    ],
]);

if ($form->loadData() && $form->validate()) {
    //get data form form
    $formData = $form->getValue();
    //here is some action if form's data is valid, e.g. mail() or redirect
}

echo $form;
```

In that case form with address will be render three times with different `name` parameters.
