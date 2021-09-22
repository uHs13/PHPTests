<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase
{
    /**
     * @expectedException TypeError
    */
    public function testShoudThrowAnErrorInTypeMismatch(): void
    {

        $this->expectException(\TypeError::class);

        $card = new CreditCardExpirationValidator(
            date('Y-m-d', strtotime('+1 day'))
        );

        $card->isValid();

    }

    /**
     * @dataProvider dataProvider
     * */
    public function testIsValid(
        $value,
        bool $expected
    ):void {

        $card = new CreditCardExpirationValidator($value);

        $this->assertEquals(
            $expected,
            $card->isValid()
        );

    }

    public function dataProvider(): array
    {
        return [
            'shouldBeValidWhenExpirationIsAfterToday' => [
                'value' => new \DateTime(date('Y-m-d', strtotime('+1 day'))),
                'expected' => true
            ],
            'shouldNotBeValidWhenExpirationIsBeforeToday' => [
                'value' => new \DateTime(date('Y-m-d', strtotime('-1 day'))),
                'expected' => false
            ],
            'shouldNotBeValidWhenExpirationIsToday' => [
                'value' => new \DateTime(date('Y-m-d')),
                'expected' => false
            ]
        ];
    }
}