<?php

namespace PaymentBundle\Test\Service;

use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Service\PaymentService;
use PaymentBundle\Service\Gateway;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private $paymentTransaction;
    private $paymentService;
    private $creditCard;
    private $customer;
    private $gateway;
    private $item;

    public function setUp(): void
    {
        $this->gateway = $this->createMock(Gateway::class);

        $this->paymentTransaction = $this->createMock(
            PaymentTransactionRepository::class
        );

        $this->customer = $this->createMock(Customer::class);

        $this->item = $this->createMock(Item::class);

        $this->creditCard = $this->createMock(CreditCard::class);

        $this->paymentService = new PaymentService(
            $this->gateway,
            $this->paymentTransaction
        );
    }

    /**
     * @test
     * */
    public function shouldSaveWhenGatewayReturnTrueWithRetries(): void
    {
        $this->gateway->expects($this->atLeast(3))
        ->method('pay')
        ->will($this->onConsecutiveCalls(
            false,
            false,
            true
        ));

        $this->paymentTransaction->expects($this->once())
        ->method('save');

        $this->paymentService->pay(
            $this->customer,
            $this->item,
            $this->creditCard
        );
    }

    /**
     * @test
     * */
    public function shouldSaveWhenGatewayReturnTrue(): void
    {
        $this->gateway->expects($this->once())
        ->method('pay')
        ->willReturn(true);

        $this->paymentTransaction->expects($this->once())
        ->method('save');

        $this->paymentService->pay(
            $this->customer,
            $this->item,
            $this->creditCard
        );
    }

    /**
     * @expectedException PaymentBundle\Exception\PaymentErrorException
     * @test
     * */
    public function shouldThrowAnExceptionWhenGatewayFails(): void
    {
        $this->gateway->expects($this->atLeast(3))
        ->method('pay')
        ->will($this->onConsecutiveCalls(
            false,
            false,
            false
        ));

        $this->paymentTransaction->expects($this->never())
        ->method('save');

        $this->expectException(PaymentErrorException::class);

        $this->paymentService->pay(
            $this->customer,
            $this->item,
            $this->creditCard
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->gateway,
            $this->paymentTransaction,
            $this->customer,
            $this->item,
            $this->creditCard,
            $this->paymentService
        );
    }
}