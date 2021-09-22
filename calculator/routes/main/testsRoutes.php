<?php

use Psr\Http\Message\ServerRequestInterface as Req;
use Psr\Http\Message\ResponseInterface as Res;
use Calculator\Tests\Calculator\Calculator;
use Calculator\RunTests\RunTests;

$app->get('/test', function (Req $req, Res $res, array $args) {

	$tests = new Calculator();

	$tests->shouldApplyWhenValueIsHigherThanMinimum();

	return $res;

});

$app->get('/tester', function (Req $req, Res $res, array $args) {

	$run = new RunTests();

	$run->run();

	return $res;

});