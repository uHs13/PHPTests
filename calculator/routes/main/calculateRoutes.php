<?php

use Calculator\DiscountCalculator\DiscountCalculator;
use Psr\Http\Message\ServerRequestInterface as Req;
use Psr\Http\Message\ResponseInterface as Res;

$app->get('/discount/{value}', function (Req $req, Res $res, array $args) {

	$discount = new DiscountCalculator();
	echo $discount->calculate((float) $args['value']);

	return $res;

});