<?php

namespace PaymentBundle\Test\Service;

use OrderBundle\Entity\CreditCard;
use OrderBundle\Entity\Customer;
use OrderBundle\Entity\Item;
use PaymentBundle\Exception\PaymentErrorException;
use PaymentBundle\Repository\PaymentTransactionRepository;
use PaymentBundle\Service\Gateway;
use PaymentBundle\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    /**
     * @test
     * */
    public function shouldSaveWhenGatewayReturnTrueWithRetries(): void
    {
        $gateway = $this->createMock(Gateway::class);
        
        $paymentTransaction = $this->createMock(
            PaymentTransactionRepository::class
        );
        
        $customer = $this->createMock(Customer::class);
        
        $item = $this->createMock(Item::class);
        
        $creditCard = $this->createMock(CreditCard::class);

        $paymentService = new PaymentService(
            $gateway,
            $paymentTransaction
        );

        $gateway->expects($this->atLeast(3))
        ->method('pay')
        ->will($this->onConsecutiveCalls(
            false,
            false,
            true
        ));

        $paymentTransaction->expects($this->once())
        ->method('save');

        $paymentService->pay(
            $customer,
            $item,
            $creditCard
        );
    }
}