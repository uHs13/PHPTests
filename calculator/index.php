<?php

require_once 'vendor/autoload.php';

use Calculator\Configuration\ErrorMiddleware\ErrorMiddleware;
use Calculator\Configuration\Slim\Slim;

$slim = new Slim();
$slim->configure('/tests/calculator');
$app = $slim->getApp();

$errorMiddleware = new ErrorMiddleware($app);

require_once 'routes/main/testsRoutes.php';
require_once 'routes/main/calculateRoutes.php';

$app->run();