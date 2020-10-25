<?php

namespace InsuranceTools\Tests\Unit;

use InsuranceTools\Insurance\Exception\InvalidArgumentException;
use InsuranceTools\Insurance\ProviderAggregate;
use InsuranceTools\Insurance\UniqueProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProviderAggregateTest extends TestCase
{
    /**
     * @covers \InsuranceTools\Insurance\ProviderAggregate::__construct()
     * @covers \InsuranceTools\Insurance\ProviderAggregate::addProvider()
     */
    public function testConstructUniqueProviders()
    {
        $provider1 = $this->mockUniqueProviderInterface();
        $provider1
            ->method('getId')
            ->willReturn('123')
        ;
        $provider2 = $this->mockUniqueProviderInterface();
        $provider2
            ->method('getId')
            ->willReturn('12345')
        ;

        $provider = new ProviderAggregate([$provider1, $provider2]);
        $this->assertNotNull($provider);
    }

    /**
     * @covers \InsuranceTools\Insurance\ProviderAggregate::__construct()
     * @covers \InsuranceTools\Insurance\ProviderAggregate::addProvider()
     */
    public function testConstructNonUniqueProviders()
    {
        $this->expectException(InvalidArgumentException::class);

        $provider1 = $this->mockUniqueProviderInterface();
        $provider1
            ->method('getId')
            ->willReturn('123')
        ;
        $provider2 = $this->mockUniqueProviderInterface();
        $provider2
            ->method('getId')
            ->willReturn('123')
        ;

        new ProviderAggregate([$provider1, $provider2]);
    }

    /**
     * @covers \InsuranceTools\Insurance\ProviderAggregate::__construct()
     * @covers \InsuranceTools\Insurance\ProviderAggregate::addProvider()
     * @covers \InsuranceTools\Insurance\ProviderAggregate::getQuote()
     */
    public function testGetQuote()
    {
        $expectedProvider1Data = '1234asdf';
        $expectedProvider2Data1 = 'xxx23242342';
        $expectedProvider2Data2 = 'yyy23242342';
        $provider1Key = '123';
        $provider2Key = '234';

        $provider1 = $this->mockUniqueProviderInterface();
        $provider1
            ->method('getId')
            ->willReturn($provider1Key)
        ;
        $provider1
            ->expects($this->once())
            ->method('getQuote')
            ->willReturnCallback(fn() => yield $expectedProvider1Data)
        ;
        $provider2 = $this->mockUniqueProviderInterface();
        $provider2
            ->method('getId')
            ->willReturn($provider2Key)
        ;
        $provider2
            ->expects($this->once())
            ->method('getQuote')
            ->willReturnCallback(
                function () use ($expectedProvider2Data1, $expectedProvider2Data2) {
                    yield $expectedProvider2Data1;
                    yield $expectedProvider2Data2;
                }
            )
        ;

        $provider = new ProviderAggregate([$provider1, $provider2]);
        $quotas = $provider->getQuote();
        $this->assertIsIterable($quotas);
        $quotasArr = iterator_to_array($quotas);

        $provider1Data = iterator_to_array($quotasArr[$provider1Key]);
        $provider2Data = iterator_to_array($quotasArr[$provider2Key]);

        $this->assertCount(2, $quotasArr);
        $this->assertEquals([$expectedProvider1Data], $provider1Data);
        $this->assertEquals([$expectedProvider2Data1, $expectedProvider2Data2], $provider2Data);
    }

    /**
     * @return UniqueProviderInterface|MockObject
     */
    protected function mockUniqueProviderInterface()
    {
        return $this->getMockBuilder(UniqueProviderInterface::class)
            ->onlyMethods(
                [
                    'getQuote',
                    'getId',
                ]
            )
            ->getMock()
            ;
    }
}
