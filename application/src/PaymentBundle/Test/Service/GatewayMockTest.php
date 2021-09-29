<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayMockTest extends TestCase
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
     * @test1
     * */
    public function shouldNotBeValidIfAuthenticationFails(): void
    {
        $invalidUser = 'invalid-user';
        $invalidPassword = 'invalid-password';

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())
        ->method('send')
        ->will($this->returnValueMap([
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $invalidUser,
                    'password' => $invalidPassword
                ],
                false
            ]
        ]));

        $allMessages = [];
        $logger = $this->getLogger($allMessages);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            $invalidUser,
            $invalidPassword
        );

        $this->assertFalse($gateway->pay(
            ...$this->getPayData()
        ));

        $this->assertEquals(
            ['Authentication failed'],
            $allMessages
        );
    }

    /**
     * @test
     * @dataProvider dataProvider
     * */
    public function isValid(
        string $validUser,
        string $validPassword,
        string $name,
        string $token,
        int $creditCardNumber,
        float $value,
        array $loggedMessages,
        array $allMessages,
        \DateTime $validity,
    ): void {

        $logger = $this->getLogger($allMessages);

        $httpClient = $this->createMock(HttpClientInterface::class);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            $validUser,
            $validPassword
        );

        $httpClient->expects($this->atLeast(2))
        ->method('send')
        ->will($this->returnValueMap([
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $validUser,
                    'password' => $validPassword
                ],
                $token
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => $name,
                    'credit_card_number' => $creditCardNumber,
                    'validity' => $validity,
                    'value' => $value,
                    'token' => $token
                ],
                ['paid' => false]
            ]
        ]));

        $this->assertFalse($gateway->pay(
            $name,
            $creditCardNumber,
            $value,
            $validity,
        ));

        if (count($loggedMessages) > 0) {

            $this->assertEquals(
                $loggedMessages,
                $allMessages
            );

        }
    }

    public function dataProvider(): array
    {
        return [
            'shouldNotBeValidWhenPaymentFails' => [
                'validUser' => 'valid-user',
                'validPassword' => 'valid-password',
                'name' => 'name',
                'token' => 'returned-token',
                'creditCardNumber' => 123456789,
                'value' => 99.8,
                'loggedMessages' => ['Payment failed'],
                'allMessages' => [],
                'validity' => new \DateTime(date('Y-m-d', strtotime('+1 day'))),
            ],
            'shouldBeValidWhenDataIsOk' => [
                'validUser' => 'valid-user',
                'validPassword' => 'valid-password',
                'name' => 'name',
                'token' => 'returned-token',
                'creditCardNumber' => 123456789,
                'value' => 99.8,
                'loggedMessages' => [],
                'allMessages' => [],
                'validity' => new \DateTime(date('Y-m-d', strtotime('+1 day'))),
            ],
        ];
    }
}