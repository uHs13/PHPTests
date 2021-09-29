<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayMockReturnOrderTest extends TestCase
{
    public function getLogger(array &$logMessages)
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('log')
        ->will($this->returnCallback(
            function ($message) use (&$logMessages) {
                array_push($logMessages, $message);
            }
        ));

        return $logger;
    }

    public function getGateway(
        $httpClient,
        $logger,
        string $user,
        string $password
    ): Gateway {

        return new Gateway(
            $httpClient,
            $logger,
            $user,
            $password
        );
    }

    public function getPayData(): array
    {
        return [
            'name',
            123456789,
            99.8,
            new \DateTime(date('Y-m-d', strtotime('+1 day')))
        ];
    }

    /**
     * @test
     * */
    public function shouldNotBeValidWhenAuthenticationFails(): void {

        $loggedMessages = ['Authentication failed'];
        $validUser = 'valid-user';
        $validPassword = 'valid-password';

        $allMessages = [];
        $logger = $this->getLogger($allMessages);

        $httpClient = $this->createMock(HttpClientInterface::class);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            $validUser,
            $validPassword
        );

        $httpClient->expects($this->at(0))
        ->method('send')
        ->willReturn(false);

        $this->assertFalse($gateway->pay(
            ...$this->getPayData()
        ));

        $this->assertEquals(
            $loggedMessages,
            $allMessages
        );
    }

    /**
     * @test
     * */
    public function shouldNotBeValidWhenPaymentFails(): void {

        $loggedMessages = ['Payment failed'];
        $validUser = 'valid-user';
        $validPassword = 'valid-password';

        $allMessages = [];
        $logger = $this->getLogger($allMessages);

        $httpClient = $this->createMock(HttpClientInterface::class);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            $validUser,
            $validPassword
        );

        $httpClient->expects($this->at(0))
        ->method('send')
        ->willReturn('true');

        $httpClient->expects($this->at(1))
        ->method('send')
        ->willReturn(['paid' => false]);

        $this->assertFalse($gateway->pay(
            ...$this->getPayData()
        ));

        $this->assertEquals(
            $loggedMessages,
            $allMessages
        );
    }

    /**
     * @test
     * */
    public function shouldNotBeValidWhenDataIsOk(): void {

        $loggedMessages = ['Payment failed'];
        $validUser = 'valid-user';
        $validPassword = 'valid-password';

        $logger = $this->createMock(LoggerInterface::class);

        $httpClient = $this->createMock(HttpClientInterface::class);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            $validUser,
            $validPassword
        );

        $httpClient->expects($this->at(0))
        ->method('send')
        ->willReturn('true');

        $httpClient->expects($this->at(1))
        ->method('send')
        ->willReturn(['paid' => true]);

        $this->assertTrue($gateway->pay(
            ...$this->getPayData()
        ));
    }
}