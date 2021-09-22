<?php

namespace Calculator\Tests\ToTest;

use Calculator\DiscountCalculator\DiscountCalculator;
use Calculator\Tests\Assert\Assert;

class ToTest
{
	public function shouldApplyWhenValueIsHigherThanMinimum()
	{

		$calculator = new DiscountCalculator();

		$total = $calculator->calculate(1100);

		Assert::assert(880.0, $total);

	}
}