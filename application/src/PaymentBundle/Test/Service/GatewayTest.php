<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    public function fakeResponse(
        array $body,
        $validAuthentication,
        $invalidAuthentication
    ) {

        return (
            $body['user'] === 'valid-user'
            &&
            $body['password'] === 'valid-password'
        ) ? $validAuthentication : $invalidAuthentication;
    }

    public function fakeHttpClientSend(
        string $method,
        string $address,
        array $body,
        bool $validPay
    ) {

        switch ($address) {
            case Gateway::BASE_URL . '/authenticate':
            return $this->fakeResponse(
                $body,
                true,
                false
            );
            break;

            case Gateway::BASE_URL . '/pay':
            return ($validPay)
            ? ['paid' => true]
            : ['paid' => false];
            break;
        }
    }

    public function getHttpClient(bool $validPay = false)
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
        ->willReturn($this->returnCallback(
            function ($method, $address, $body) use ($validPay){
                return $this->fakeHttpClientSend(
                    $method,
                    $address,
                    $body,
                    $validPay
                );
            }
        ));

        return $httpClient;
    }

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
    public function shouldNotBeValidIfAuthenticationFails()
    {
        $httpClient = $this->getHttpClient();

        $allMessages = [];
        $logger = $this->getLogger($allMessages);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            'invalid-user',
            'invalid-password'
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
     * */
    public function shouldNotBeValidIfPaymentFails()
    {
        $httpClient = $this->getHttpClient();

        $allMessages = [];
        $logger = $this->getLogger($allMessages);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            'valid-user',
            'valid-password'
        );

        $this->assertFalse($gateway->pay(
            ...$this->getPayData()
        ));

        $this->assertEquals(
            ['Payment failed'],
            $allMessages
        );
    }

    /**
     * @test
     * */
    public function shouldBeValidIfDataIsOk()
    {
        $httpClient = $this->getHttpClient(true);

        $logger = $this->createMock(LoggerInterface::class);

        $gateway = $this->getGateway(
            $httpClient,
            $logger,
            'valid-user',
            'valid-password'
        );

        $this->assertTrue($gateway->pay(
            ...$this->getPayData()
        ));
    }
}