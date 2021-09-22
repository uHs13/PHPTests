<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    /**
    * @dataProvider valueProvider
    */
    public function testIsValid(
        string $value,
        bool $expected
    ): void {

        $validator = new NotEmptyValidator($value);

        $this->assertEquals(
            $expected,
            $validator->isValid()
        );

    }

    public function valueProvider(): array
    {
        return [
            'shouldBeValidWhenValueIsNotEmpty' => [
                'value' => 'text',
                'expected' => true
            ],
            'shouldNotBeValidWhenValueIsEmpty' => [
                'value' => '',
                'expected' => false
            ]
        ];
    }
}