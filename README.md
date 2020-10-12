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

```bash
composer req marvin255/serviform
```


Basic usage
-----------

Use `\marvin255\serviform\helpers\FactoryFields` to create form. For each element must be specified an array with required `type` key or set an object that implements `\marvin255\serviform\interfaces\Field` interface. For each rule must be specified an array like shown below.

```php

use marvin255\serviform\helpers\FactoryFields;

$form = FactoryFields::initElement('form', [
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

use marvin255\serviform\helpers\FactoryField;

$form = FactoryFields::initElement('form', [
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

use marvin255\serviform\helpers\FactoryField;

$form = FactoryFields::initElement('form', [
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



Fields
------

All fields must implement `\marvin255\serviform\interfaces\Field`. To add new field type to factory or change old one use `\marvin255\serviform\helpers\FactoryFields::setDescription`.

Add new field type.

```php
use serviform\helpers\FactoryFields;

FactoryFields::setDescription('new_field_type', [
    'type' => '\My\Awesome\Field', // required, string with the name of new type class that implements \marvin255\serviform\interfaces\Field
    'label' => 'Default label', // we can set any default setting for each of newly created fields
    'attributes' => [
        'class' => 'form-control',
    ],
]);
```

Redefine old type.

```php
use serviform\helpers\FactoryFields;

FactoryFields::setDescription('input', [
    'type' => '\My\Awesome\Input', // we can set new class for builtin field types
    'label' => 'Default label', // we can set any default setting for each of newly created fields
]);
```



Validation rules
----------------

All validation rules must implement `\marvin255\serviform\interfaces\Validator`. To add new validation rule to factory or change old one use `\marvin255\serviform\helpers\FactoryValidators::setDescription`.

Add new rule.

```php
use serviform\helpers\FactoryValidators;

FactoryValidators::setDescription('new_rule', [
    'type' => '\My\Awesome\Rule', // required, string with the name of new rule class that implements \marvin255\serviform\interfaces\Validator
    'skipOnError' => true, // we can set any default setting for each of newly created rule
]);
```

Redefine old rule.

```php
use serviform\helpers\FactoryValidators;

FactoryFields::setDescription('require', [
    'type' => '\My\Awesome\Require', // we can set new class for builtin rule
    'skipOnError' => true, // we can set any default setting for each of newly created rules
]);
```
