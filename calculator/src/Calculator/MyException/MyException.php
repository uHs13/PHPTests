<?php

namespace Calculator\MyException;

use \Exception;

class MyException
{
	public static function throw(string $message, int $status = 1)
	{
		throw new Exception($message, $status);
	}
}