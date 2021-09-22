<?php

namespace Calculator\DiscountCalculator;

class DiscountCalculator
{
	private const MINIMUN_VALUE = 100;
	private const DISCOUNT = 0.2;

	public function calculate(float $value): float
	{
		return ($value > self::MINIMUN_VALUE)
		? $value - ($value * self::DISCOUNT)
		: $value;
	}
}