<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase
{
    public function getValidator(array $words): BadWordsValidator
    {
        $badWordsRepository = $this->createMock(
            BadWordsRepository::class
        );

        $badWordsRepository
        ->method('findAllAsArray')
        ->willReturn($words);

        return new BadWordsValidator($badWordsRepository);
    }

    /**
     * @test
     * @dataProvider dataProvider
     * */
    public function hasBadWords(
        array $words,
        string $phrase,
        bool $expected
    ): void {

        $badWordsValidator = $this->getValidator($words);

        $this->assertEquals(
            $expected,
            $badWordsValidator->hasBadWords($phrase)
        );
    }

    public function dataProvider(): array
    {
        return [
            'shouldNotBeValidIfHasBadWord' => [
                'words' => ['badword', 'worstword', 'uglyword'],
                'phrase' => 'This phrase contains a worstword',
                'expected' => true
            ],
            'shouldBeValidIfHasNoOneBadWordInPhrase' => [
                'words' => ['badword', 'worstword', 'uglyword'],
                'phrase' => 'This phrase do not contains an invalid word',
                'expected' => false
            ],
            'shouldBeValidIfBadWordsArrayIsEmpty' => [
                'words' => [],
                'phrase' => 'This phrase do not contains an invalid word',
                'expected' => false
            ],
            'shouldBeValidIfPhraseIsEmpty' => [
                'words' => [],
                'phrase' => 'This phrase do not contains an invalid word',
                'expected' => false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderTypeMismatch
     * @expectedException TypeError
     * */
    public function shouldNotBeValidInTypeMismatch(
        $words,
        $phrase
    ): void {

        $this->expectException(\TypeError::class);

        $badWordsValidator = $this->getValidator($words);

        $badWordsValidator->hasBadWords($phrase);
    }

    public function dataProviderTypeMismatch(): array
    {
        return [
            'shouldNotBeValidWhenWordsIsNotArray' => [
                'words' => 'badword',
                'phrase' => 'just a phrase',
            ],
            'shouldNotBeValidWhenPhraseIsNotString' => [
                'words' => ['badword'],
                'phrase' => ['just a phrase'],
            ],
        ];
    }
}