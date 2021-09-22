<?php

namespace OrderBundle\Entity;

class Customer
{
    private string $name;
    private string $phone;
    private bool $isActive;
    private bool $isBlocked;
    private int $id;
    private int $totalOrders;
    private int $totalRatings;
    private int $totalRecommendations;

    public function __construct(
        string $name = null,
        string $phone = null,
        bool $isActive = null,
        bool $isBlocked = null
    ) {
        $this->name = $name;
        $this->phone = $phone;
        $this->isActive = $isActive;
        $this->isBlocked = $isBlocked;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function isAllowedToOrder()
    {
        return $this->isActive && !$this->isBlocked;
    }

    public function getTotalOrders()
    {
        return $this->totalOrders;
    }

    public function setTotalOrders($totalOrders)
    {
        $this->totalOrders = $totalOrders;
    }

    public function getTotalRatings()
    {
        return $this->totalRatings;
    }

    public function setTotalRatings($totalRatings)
    {
        $this->totalRatings = $totalRatings;
    }

    public function setTotalRecommendations($totalRecommendations)
    {
        $this->totalRecommendations = $totalRecommendations;
    }

    public function getTotalRecommendations()
    {
        return $this->totalRecommendations;
    }
}