<?php

declare(strict_types = 1);

namespace OrderBundle\Test\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
	/**
	 * @test
	 * @dataProvider dataProviderTypeMismatch
	 * */
	public function shouldThrowAnErrorInTypeMismatch(
		$name,
		$phone,
		$isActive,
		$isBlocked
	): void {

		$this->expectException(\TypeError::class);

		$customer = new Customer(
			$name,
			$phone,
			$isActive,
			$isBlocked
		);

		$customer->isAllowedToOrder();

	}

	public function dataProviderTypeMismatch(): array
	{
		return [
			'shouldThrowAnErrorInNameTypeMismatch' => [
				'name' => 789465,
				'phone' => '9854713565',
				'isActive' => true,
				'isBlocked' => true
			],
			'shouldThrowAnErrorInPhoneTypeMismatch' => [
				'name' => 'Joseph Matias',
				'phone' => 9854713565,
				'isActive' => true,
				'isBlocked' => true
			],
			'shouldThrowAnErrorInIsActiveTypeMismatch' => [
				'name' => 'Joseph Matias',
				'phone' => 9854713565,
				'isActive' => 'true',
				'isBlocked' => true
			],
			'shouldThrowAnErrorInIsBlockedTypeMismatch' => [
				'name' => 'Joseph Matias',
				'phone' => 9854713565,
				'isActive' => true,
				'isBlocked' => 'true'
			],
		];
	}

	/**
	 * @dataProvider dataProvider
	 * */
	public function testIsAllowedToOrder(
		string $name,
		string $phone,
		bool $isActive,
		bool $isBlocked,
		bool $expected
	): void {

		$customer = new Customer(
			$name,
			$phone,
			$isActive,
			$isBlocked
		);

		$this->assertEquals(
			$expected,
			$customer->isAllowedToOrder()
		);

	}

	public function dataProvider(): array
	{
		return [
			'shoudNotBeValidIfIsNotActive' => [
				'name' => 'Name',
				'phone' => '96587412546',
				'isActive' => false,
				'isBlocked' => false,
				'expected' => false
			],
			'shoudNotBeValidIfIsBlocked' => [
				'name' => 'Name2',
				'phone' => '12347412546',
				'isActive' => true,
				'isBlocked' => true,
				'expected' => false
			],
			'shoudNotBeValidIfIsActiveAndNotBlocked' => [
				'name' => 'Name2',
				'phone' => '12347412546',
				'isActive' => true,
				'isBlocked' => true,
				'expected' => false
			],
		];
	}
}