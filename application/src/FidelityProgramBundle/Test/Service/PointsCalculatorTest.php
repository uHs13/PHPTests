<?php

namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Service\PointsCalculator;
use PHPUnit\Framework\TestCase;

class PointsCalculatorTest extends TestCase
{
   private $pointsCalculator;

   public function setUp(): void
   {
      $this->pointsCalculator = new PointsCalculator();
   }

   /**
    * @test
    * @dataProvider dataProvider
    * */
   public function isValid(
      $value,
      int $expected
   ): void {

      $this->assertEquals(
         $expected,
         $this->pointsCalculator->calculatePointsToReceive($value)
      );
   }

   public function dataProvider(): array
   {
      return [
         'shouldReturn50TimesWhenGreaterThan100' => [
            'value' => 101,
            'expected' => 5050
         ],
         'shouldReturn30TimesWhenGreaterThan70' => [
            'value' => 71,
            'expected' => 2130
         ],
         'shouldReturn20TimesWhenGreaterThan50' => [
            'value' => 51,
            'expected' => 1020
         ],
         'shouldReturn0WhenLowerThan50' => [
            'value' => 49,
            'expected' => 0
         ],
         'shouldReturn0WhenEmpty' => [
            'value' => '',
            'expected' => 0
         ],
         'shouldReturn0WhenNull' => [
            'value' => null,
            'expected' => 0
         ],
      ];
   }
}