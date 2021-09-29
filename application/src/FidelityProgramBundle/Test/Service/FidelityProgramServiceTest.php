<?php

namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Test\Service\PointsRepositorySpy;
use FidelityProgramBundle\Service\FidelityProgramService;
use FidelityProgramBundle\Service\PointsCalculator;
use FidelityProgramBundle\Repository\PointsRepository;
use MyFramework\LoggerInterface;
use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class FidelityProgramServiceTest extends TestCase
{
    /**
     * @test
     * */
    public function shouldSaveWhenReceivePoints(): void
    {
        //STUB AND MOCK
        $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsRepository->expects($this->once())
        ->method('save');

        /* SPY
        $pointsRepository = new PointsRepositorySpy();
        */

        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $pointsCalculator->method('calculatePointsToReceive')
        ->willReturn(100);

        //$loggerInterface = $this->createMock(LoggerInterface::class);
        $allMessages = [];

        $loggerInterface = $this->createMock(LoggerInterface::class);
        $loggerInterface->method('log')
        ->will($this->returnCallback(
            function ($message) use (&$allMessages) {
                array_push($allMessages, $message);
            }
        ));

        $fidelity = new FidelityProgramService(
            $pointsRepository,
            $pointsCalculator,
            $loggerInterface
        );

        $fidelity->addPoints(
            $this->createMock(Customer::class),
            100
        );

        /* SPY
        $this->assertTrue(
            $pointsRepository->wasCalled()
        );
        */

        $expectedMessages = [
            'Checking points for customer',
            'Customer received points'
        ];

        $this->assertEquals(
            $expectedMessages,
            $allMessages
        );
    }

    /**
     * @test
     * */
    public function shouldNotSaveWhenReceiveZeroPoints(): void
    {
        $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsRepository->expects($this->never())
        ->method('save');

        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $pointsCalculator->method('calculatePointsToReceive')
        ->willReturn(0);

        $loggerInterface = $this->createMock(LoggerInterface::class);

        $fidelity = new FidelityProgramService(
            $pointsRepository,
            $pointsCalculator,
            $loggerInterface
        );

        $fidelity->addPoints(
            $this->createMock(Customer::class),
            100
        );
    }
}