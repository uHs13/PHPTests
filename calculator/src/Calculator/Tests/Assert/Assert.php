<?php

namespace Calculator\Tests\Assert;

use Calculator\MyException\MyException;
use Calculator\Show\Show;

class Assert
{
	public static function assert($expected, $actual): void
	{

		if ($expected !== $actual) {

			MyException::throw(
				"Expected $expected but got $actual"
			);

		};

		Show::string('Test Passed');

	}
}