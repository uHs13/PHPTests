<?php

namespace Calculator\Tests\Discount;

use Calculator\DiscountCalculator\DiscountCalculator;
use Calculator\Tests\Assert\Assert;

class Discount
{
	public function shouldApplyWhenValueIsHigherThanMinimum()
	{

		$calculator = new DiscountCalculator();

		$total = $calculator->calculate(100);

		Assert::assert(100.0, $total);

	}
}