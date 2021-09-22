<?php

namespace Calculator\Tests\Calculator;

use Calculator\DiscountCalculator\DiscountCalculator;
use Calculator\Tests\Assert\Assert;

class Calculator
{
	public function shouldApplyWhenValueIsHigherThanMinimum()
	{

		$calculator = new DiscountCalculator();

		$total = $calculator->calculate(1000);

		Assert::assert(800.0, $total);

	}
}