<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     * */
    public function testIsValid(
        $value,
        bool $expected
    ): void {

        $card = new CreditCardNumberValidator($value);

        $this->assertEquals(
            $expected,
            $card->isValid()
        );

    }

    public function dataProvider(): array
    {
        return [
            'shouldBeValidWhenIsNumericAndLengthIs16' => [
                'value' => 1234567891012135,
                'expected' => true
            ],
            'shouldBeValidWhenIsNumericAndLengthIs16String' => [
                'value' => '1234567891012135',
                'expected' => true
            ],
            'shouldNotBeValidWhenIsNotNumeric' => [
                'value' => '1234a67891012135',
                'expected' => false
            ],
            'shouldNotBeValidWhenIsNumericButLengthIsLowerThan16' => [
                'value' => 12367891012135,
                'expected' => false
            ],
            'shouldNotBeValidWhenIsNumericButLengthIsGreaterThan16' => [
                'value' => 12367891012135987,
                'expected' => false
            ],
        ];
    }
}