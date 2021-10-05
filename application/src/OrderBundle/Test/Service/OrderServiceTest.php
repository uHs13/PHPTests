<?php

namespace OrderBundle\Test\Service;

use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Exception\BadWordsFoundException;
use OrderBundle\Repository\OrderRepository;
use OrderBundle\Service\BadWordsValidator;
use OrderBundle\Service\OrderService;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Entity\PaymentTransaction;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    const DESCRIPTION = 'Just a phrase';

    private $badWordsValidator;
    private $paymentService;
    private $orderRepository;
    private $fidelityProgramService;
    private $customer;
    private $item;
    private $creditCard;
    private $orderService;

    public function setUp(): void
    {
        $this->badWordsValidator = $this->createMock(
            BadWordsValidator::class
        );

        $this->paymentService = $this->createMock(
            PaymentService::class
        );

        $this->orderRepository = $this->createMock(
            OrderRepository::class
        );

        $this->fidelityProgramService = $this->createMock(
            FidelityProgramService::class
        );

        $this->customer = $this->createMock(
            Customer::class
        );

        $this->item = $this->createMock(
            Item::class
        );

        $this->creditCard = $this->createMock(
            CreditCard::class
        );

        $this->orderService = new OrderService(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService
        );
    }

    public function getOrderServiceProcessData(): array
    {
        return [
            $this->customer,
            $this->item,
            self::DESCRIPTION,
            $this->creditCard
        ];
    }

    /**
     * @test
     * @expectedException OrderBundle\Exception\CustomerNotAllowedException
     * */
    public function shouldThrowAnExceptionWhenClientisNotAllowedToOrder()
    {
        $this->expectException(CustomerNotAllowedException::class);

        $this->customer->expects($this->once())
        ->method('isAllowedToOrder')
        ->willReturn(false);

        $this->orderService->process(
            ...$this->getOrderServiceProcessData()
        );
    }

    /**
     * @test
     * @expectedException OrderBundle\Exception\ItemNotAvailableException
     * */
    public function shouldThrowAnExceptionWhenItemisNotAvailable()
    {
        $this->expectException(ItemNotAvailableException::class);

        $this->customer
        ->method('isAllowedToOrder')
        ->willReturn(true);

        $this->item->expects($this->once())
        ->method('isAvailable')
        ->willReturn(false);

        $this->orderService->process(
            ...$this->getOrderServiceProcessData()
        );
    }

    /**
     * @test
     * @expectedException OrderBundle\Exception\BadWordsFoundException
     * */
    public function shouldThrowAnExceptionWhenDescriptionHasBadWords()
    {
        $this->expectException(BadWordsFoundException::class);

        $this->customer
        ->method('isAllowedToOrder')
        ->willReturn(true);

        $this->item
        ->method('isAvailable')
        ->willReturn(true);

        $this->badWordsValidator->expects($this->once())
        ->method('hasBadWords')
        ->willReturn(true);

        $this->orderService->process(
            ...$this->getOrderServiceProcessData()
        );
    }

    /**
     * @test
     * */
    public function shouldSuccessfullyCreateOrder()
    {
        $this->customer
        ->method('isAllowedToOrder')
        ->willReturn(true);

        $this->item
        ->method('isAvailable')
        ->willReturn(true);

        $this->badWordsValidator
        ->method('hasBadWords')
        ->willReturn(false);

        $this->paymentService->expects($this->once())
        ->method('pay')
        ->willReturn(
            $this->createMock(PaymentTransaction::class)
        );

        $this->orderRepository->expects($this->once())
        ->method('save');

        $this->fidelityProgramService->expects($this->once())
        ->method('addPoints');

        $order = $this->orderService->process(
            $this->customer,
            $this->item,
            self::DESCRIPTION,
            $this->creditCard
        );

        $this->assertNotEmpty($order);
    }

    public function tearDown(): void
    {
        unset(
            $this->badWordsValidator,
            $this->paymentService,
            $this->orderRepository,
            $this->fidelityProgramService,
            $this->customer,
            $this->item,
            $this->creditCard,
            $this->orderService,
        );
    }
}