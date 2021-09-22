<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
    * @dataProvider dataProvider
    */
    public function testIsValid(
        $value,
        bool $expected
    ): void {

        $numericValidator = new NumericValidator($value);

        $this->assertEquals(
            $expected,
            $numericValidator->isValid()
        );

    }

    public function dataProvider(): array
    {
        return [
            'shouldNotBeValidIfIsNotNumeric' => [
                'value' => '12someName',
                'expected' => false
            ],
            'shouldBeValidIfIsNumeric' => [
                'value' => 88.6,
                'expected' => true
            ],
            'shouldBeValidIfIsNumericString' => [
                'value' => '13',
                'expected' => true
            ],
            'shouldNotBeValidIfIsEmpty' => [
                'value' => '',
                'expected' => false
            ]
        ];
    }
}