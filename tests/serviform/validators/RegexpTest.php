<?php

class RegexpTest extends \tests\cases\Validator
{
    public function testConfigRegexp()
    {
        $validator = $this->getValidator();
        $validator->config(['regexp' => '[0-9]+']);
        $this->assertEquals('[0-9]+', $validator->regexp);
    }

    public function testConfigModifiers()
    {
        $validator = $this->getValidator();
        $validator->config(['modifiers' => 'iu']);
        $this->assertEquals('iu', $validator->modifiers);
    }

    public function testValidate()
    {
        $validator = $this->getValidator();
        $validator->message = 'test validator error';
        $validator->regexp = '[0-9]+';
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input1', 'input2']);

        $form->getElement('input1')->setValue('a');
        $form->getElement('input2')->setValue('b');
        $validator->validate();
        $this->assertEquals(
            [
                'input1' => ['test validator error'],
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('a');
        $form->getElement('input2')->setValue('b');
        $validator->validate(['input2']);
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('1');
        $form->getElement('input2')->setValue('b');
        $validator->validate();
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('test@test.ru');
        $form->getElement('input2')->setValue('test');
        $validator->regexp = 'email';
        $validator->validate();
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('http://test.test');
        $form->getElement('input2')->setValue('test');
        $validator->regexp = 'url';
        $validator->validate();
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );

        $form->clearErrors();
        $form->getElement('input1')->setValue('127.0.0.1');
        $form->getElement('input2')->setValue('test');
        $validator->regexp = 'ipv4';
        $validator->validate();
        $this->assertEquals(
            [
                'input2' => ['test validator error'],
            ],
            $form->getErrors()
        );
    }

    /**
     * @expectedException \serviform\Exception
     */
    public function testWrongRegexpException()
    {
        $validator = $this->getValidator();
        $validator->message = 'test validator error';
        $validator->regexp = null;
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input1', 'input2']);
        $validator->validate();
    }

    public function testSkipOnError()
    {
        $validator = $this->getValidator();
        $validator->message = 'test validator error';
        $validator->regexp = '[0-9]+';
        $validator->skipOnError = true;
        $form = $this->getTestForm();
        $validator->setParent($form);
        $validator->setElements(['input1']);

        $form->getElement('input1')->setValue('a');
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
        $validator->regexp = '[0-9]+';
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
        return new \serviform\validators\Regexp();
    }
}
