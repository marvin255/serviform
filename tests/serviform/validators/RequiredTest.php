<?php

class RequiredTest extends \tests\cases\Validator
{
    public function testValidate()
    {
        $validator = $this->getValidator();
        $validator->message = 'test validator error';
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input1', 'input2']);

        $form->getElement('input1')->setValue('');
        $form->getElement('input2')->setValue('');
        $validator->validate();
        $this->assertEquals(
            [
                'input1' => ['test validator error'],
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('');
        $form->getElement('input2')->setValue('');
        $validator->validate(['input2']);
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('0');
        $form->getElement('input2')->setValue('');
        $validator->validate();
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('0');
        $form->getElement('input2')->setValue('1');
        $validator->validate();
        $this->assertEquals(
            [],
            $form->getErrors()
        );
    }

    public function testSkipOnError()
    {
        $validator = $this->getValidator();
        $validator->message = 'test validator error';
        $validator->skipOnError = true;
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input1']);

        $form->getElement('input1')->setValue('');
        $form->getElement('input1')->addError('test skip error');

        $validator->validate();
        $this->assertEquals(
            ['input1' => ['test skip error']],
            $form->getErrors()
        );

        $validator->skipOnError = false;
        $validator->validate();
        $this->assertEquals(
            ['input1' => ['test skip error', 'test validator error']],
            $form->getErrors()
        );
    }

    public function testSkipOnEmpty()
    {
        $validator = $this->getValidator();
        $validator->message = 'test validator error';
        $validator->skipOnEmpty = true;
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input1']);

        $form->getElement('input1')->setValue('');

        $validator->validate();
        $this->assertEquals(
            [],
            $form->getErrors()
        );

        $validator->skipOnEmpty = false;
        $validator->validate();
        $this->assertEquals(
            ['input1' => ['test validator error']],
            $form->getErrors()
        );
    }

    public function getValidator()
    {
        return new \serviform\validators\Required();
    }
}
