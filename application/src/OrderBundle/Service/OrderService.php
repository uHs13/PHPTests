<?php

namespace OrderBundle\Service;

use FidelityProgramBundle\Service\FidelityProgramService;
use OrderBundle\Exception\CustomerNotAllowedException;
use OrderBundle\Exception\ItemNotAvailableException;
use OrderBundle\Exception\BadWordsFoundException;
use OrderBundle\Repository\OrderRepository;
use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Order;
use OrderBundle\Entity\Item;
use PaymentBundle\Service\PaymentService;

class OrderService
{
    private $badWordsValidator;
    private $paymentService;
    private $orderRepository;
    private $fidelityProgramService;

    public function __construct(
        BadWordsValidator $badWordsValidator,
        PaymentService $paymentService,
        OrderRepository $orderRepository,
        FidelityProgramService $fidelityProgramService
    ) {
        $this->badWordsValidator = $badWordsValidator;
        $this->paymentService = $paymentService;
        $this->orderRepository = $orderRepository;
        $this->fidelityProgramService = $fidelityProgramService;
    }

    public function process(
        Customer $customer,
        Item $item,
        $description,
        CreditCard $creditCard
    ): Order {

        $this->validateConditions(
            $customer,
            $item,
            $description
        );

        return $this->createOrder(
            $customer,
            $item,
            $description,
            $creditCard
        );
    }

    private function validateConditions(
        Customer $customer,
        Item $item,
        $description
    ): void {
        
        if (!$customer->isAllowedToOrder()) {
            throw new CustomerNotAllowedException();
        }

        if (!$item->isAvailable()) {
            throw new ItemNotAvailableException();
        }

        if ($this->badWordsValidator->hasBadWords($description)) {
            throw new BadWordsFoundException();
        }
    }

    private function createOrder(
        Customer $customer,
        Item $item,
        $description,
        CreditCard $creditCard
    ): Order {

        $paymentTransaction = $this->paymentService->pay(
            $customer,
            $item,
            $creditCard
        );

        $order = new Order(
            $customer,
            $paymentTransaction,
            $item,
            $description
        );

        $this->orderRepository->save($order);

        $this->fidelityProgramService->addPoints(
            $customer,
            $item->getValue()
        );

        return $order;
    }
}