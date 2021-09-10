<?php

declare(strict_types = 1);

namespace VV\T3elements\Tests\Unit;

use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use VV\T3elements\BaseElement;

class BaseElementTest extends UnitTestCase
{
    /**
     * Data provider for checksIfContentIsUsed
     */
    public function classNames()
    {
        return [
            [
                'Person',
                'person',
            ],
            [
                'SuperFunkySlider',
                'super_funky_slider',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider classNames
     *
     * @param string $className
     * @param string $expectedResult
     */
    public function generatesCorrectElementName(string $className, string $expectedResult): void
    {
        $mock = $this->getMockBuilder(BaseElement::class)
            ->setMockClassName($className)
            ->getMock();

        $mock->expects($this->once())
            ->method('getElementName')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $mock->getElementName());
    }
}
