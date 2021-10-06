<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Service\CustomerCategoryService;
use OrderBundle\Service\MediumUserCategory;
use OrderBundle\Service\LightUserCategory;
use OrderBundle\Service\HeavyUserCategory;
use OrderBundle\Service\NewUserCategory;
use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerCategoryServiceTest extends TestCase
{
    private CustomerCategoryService $customerCategoryService;
    private Customer $customer;

    public function setUp(): void
    {
        $this->customer = new Customer(
            'name',
            '5684712345',
            true,
            true
        );

        $this->customerCategoryService = new CustomerCategoryService();

        $this->customerCategoryService
        ->addCategory(new HeavyUserCategory());

        $this->customerCategoryService
        ->addCategory(new MediumUserCategory());

        $this->customerCategoryService
        ->addCategory(new LightUserCategory());

        $this->customerCategoryService
        ->addCategory(new NewUserCategory());
    }

    /**
     * @test
     * @dataProvider dataProvider
     * */
    public function shouldBeProperlyClassified(
        int $orders,
        int $ratings,
        int $recommendations,
        string $category
    ): void {

        $this->customer->setTotalOrders($orders);
        $this->customer->setTotalRatings($ratings);
        $this->customer->setTotalRecommendations($recommendations);

        $this->assertEquals(
            $category,
            $this->customerCategoryService
            ->getUsageCategory($this->customer)
        );
    }

    /**
     * @dataProvider
     * */
    public function dataProvider(): array
    {
        return [
            'shouldBeANewUser' => [
                'orders' => 0,
                'ratings' => 0,
                'recommendations' => 0,
                'category' => CustomerCategoryService::CATEGORY_NEW_USER
            ],
            'shouldBeALightUser' => [
                'orders' => 5,
                'ratings' => 1,
                'recommendations' => 0,
                'category' => CustomerCategoryService::CATEGORY_LIGHT_USER
            ],
            'shouldBeAMediumUser' => [
                'orders' => 20,
                'ratings' => 5,
                'recommendations' => 1,
                'category' => CustomerCategoryService::CATEGORY_MEDIUM_USER
            ],
            'shouldBeAHeavyUser' => [
                'orders' => 50,
                'ratings' => 10,
                'recommendations' => 5,
                'category' => CustomerCategoryService::CATEGORY_HEAVY_USER
            ],
        ];
    }

    public function tearDown(): void
    {
        unset($this->customerCategoryService);
    }
}