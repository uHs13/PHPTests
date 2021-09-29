<?php

namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Repository\PointsRepositoryInterface;

class PointsRepositorySpy implements PointsRepositoryInterface
{
	private $called;

	public function __construct()
	{
		$this->called = false;
	}

	public function save($points)
	{
		$this->called = true;
	}

	public function wasCalled()
	{
		return $this->called;
	}
}