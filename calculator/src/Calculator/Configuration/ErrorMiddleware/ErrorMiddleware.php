<?php

namespace Calculator\Configuration\ErrorMiddleware;

use Calculator\Configuration\ErrorMiddleware\ErrorHandler;
use Slim\App;

class ErrorMiddleware
{
	private $errorMiddleware;

	public function getErrorMiddleware()
	{
		return $this->errorMiddleware;
	}

	public function __construct(App $app)
	{
		$this->errorMiddleware = $app
		->addErrorMiddleware(true, true, true);

		$this->setDefaultErrorHandler($app);
	}

	public function setDefaultErrorHandler(App $app): void
	{

		$this->errorMiddleware->setDefaultErrorHandler(
			ErrorHandler::get($app)
		);

	}
}