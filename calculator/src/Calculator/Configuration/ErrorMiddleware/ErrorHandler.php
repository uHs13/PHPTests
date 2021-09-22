<?php

namespace Calculator\Configuration\ErrorMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use \Trowable;
use Slim\App;

class ErrorHandler
{
	public static function get(App $app)
	{

		return function (
			ServerRequestInterface $request,
			$exception
		) use ($app) {

			$type = get_class($exception);

			$response = $app->getResponseFactory()->createResponse();

			if ($type === 'Slim\Exception\HttpNotFoundException') {

				$errorArray = [
					'info' => 'page not found'
				];

			} else {

				$errorArray = [
					'error' => $exception->getMessage(),
					'local' => $exception->getFile(),
					'line' => $exception->getLine()
				];

			}

			$response->getBody()->write(
				json_encode($errorArray, JSON_PRETTY_PRINT)
			);

			return $response;

		};

	}
}