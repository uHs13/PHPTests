<?php

namespace Calculator\RunTests;

use Calculator\Tester\Tester;
use Calculator\Show\Show;

class RunTests
{
	private Tester $tester;

	public function __construct()
	{
		$this->tester = new Tester(
			$_SERVER['DOCUMENT_ROOT'] .
			DIRECTORY_SEPARATOR .
			'tests' .
			DIRECTORY_SEPARATOR .
			'calculator' .
			DIRECTORY_SEPARATOR .
			'src' .
			DIRECTORY_SEPARATOR .
			'Calculator' .
			DIRECTORY_SEPARATOR .
			'Tests' .
			DIRECTORY_SEPARATOR
		);
	}

	public function run()
	{
		foreach ($this->tester->getTestClasses() as $class) {

			$class = "Calculator\Tests\\$class\\$class";

			$object = new $class();

			$methods = get_class_methods($object);

			foreach ($methods as $method) {

				Show::string("class: $class | method: $method ");
				Show::string('| result: ');
				$object->$method();
				Show::string('<br>');

			}

		}
	}
}