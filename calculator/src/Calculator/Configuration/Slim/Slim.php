<?php

namespace Calculator\Configuration\Slim;

use Slim\Factory\AppFactory as App;

class Slim
{
	private $app;

	public function getApp()
	{
		return $this->app;
	}

	public function __construct()
	{
		$this->app = App::create();
	}

	public function configure(string $basePath)
	{
		$this->app->setBasePath($basePath);
		$this->app->addRoutingMiddleware();
	}
}