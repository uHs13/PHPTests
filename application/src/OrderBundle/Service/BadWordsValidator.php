<?php

namespace OrderBundle\Service;

use OrderBundle\Repository\BadWordsRepositoryInterface;

class BadWordsValidator
{
    private $badWordsRepository;

    public function __construct(BadWordsRepositoryInterface $badWordsRepository)
    {
        $this->badWordsRepository = $badWordsRepository;
    }

    public function hasBadWords(string $text)
    {
        $allBadWords = $this->badWordsRepository->findAllAsArray();
        foreach ($allBadWords as $badWord) {
            if (strpos($text, $badWord)) {
                return true;
            }
        }

        return false;
    }
}