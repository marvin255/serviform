<?php

namespace marvin255\serviform\tests\serviform\validators;

use marvin255\serviform\tests\cases\Validator;
use marvin255\serviform\helpers\FactoryValidators;

class FileTest extends Validator
{
    public function testSetExtensions()
    {
        $validator = $this->getValidator();
        $extensions = ['png', 'jpg'];
        $this->assertSame($validator, $validator->setExtensions($extensions));
        $this->assertSame($extensions, $validator->getExtensions());
    }

    public function testSetMimes()
    {
        $validator = $this->getValidator();
        $mimes = ['text/plain', 'image/jpeg'];
        $this->assertSame($validator, $validator->setMimes($mimes));
        $this->assertSame($mimes, $validator->getMimes());
    }

    public function testSetMaxSize()
    {
        $validator = $this->getValidator();
        $this->assertSame($validator, $validator->setMaxSize('12'));
        $this->assertSame(12, $validator->getMaxSize());
        $this->assertSame(0, $validator->setMaxSize('test')->getMaxSize());
        $this->assertSame(13, $validator->setMaxSize(13)->getMaxSize());
    }

    /**
     * Return array values to test validate.
     */
    protected function getValidatorProvider()
    {
        $file = [
            'tmp_name' => $this->loadedFile,
            'name' => 'test.txt',
            'type' => 'text/plain',
            'error' => 0,
        ];

        return [
            'empty file' => [null, false],
            'wrong type' => ['test.txt', false],
            'error in loading' => [
                array_merge($file, ['error' => 4]),
                false,
                ['extensions' => ['txt'], 'mimes' => ['text/plain'], 'maxSize' => 10000000],
            ],
            'wrong extension' => [
                $file,
                false,
                ['extensions' => ['jpg'], 'mimes' => ['text/plain'], 'maxSize' => 10000000],
            ],
            'allowed extension' => [
                $file,
                true,
                ['extensions' => ['txt'], 'mimes' => ['text/plain'], 'maxSize' => 10000000],
            ],
            'wrong mime' => [
                $file,
                false,
                ['extensions' => ['txt'], 'mimes' => ['image/jpeg'], 'maxSize' => 10000000],
            ],
            'allowed mime' => [
                $file,
                true,
                ['extensions' => ['txt'], 'mimes' => ['text/plain'], 'maxSize' => 10000000],
            ],
            'wrong size' => [
                $file,
                false,
                ['extensions' => ['txt'], 'mimes' => ['text/plain'], 'maxSize' => 1],
            ],
            'allowed size' => [
                $file,
                true,
                ['extensions' => ['txt'], 'mimes' => ['text/plain'], 'maxSize' => 10000000],
            ],
        ];
    }

    /**
     * Return object for validator representation.
     */
    protected function getValidator(array $options = [])
    {
        $type = '\\marvin255\\serviform\\validators\\File';

        return FactoryValidators::initElement($type, $options);
    }

    protected $loadedFile = null;

    public function setUp()
    {
        parent::setUp();
        $this->loadedFile = tempnam(sys_get_temp_dir(), mt_rand());
        file_put_contents($this->loadedFile, 'text');
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink($this->loadedFile);
    }
}
