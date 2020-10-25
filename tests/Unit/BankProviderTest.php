<?php

namespace InsuranceTools\Tests\Unit;

use InsuranceTools\Insurance\BankDataSourceInterface;
use InsuranceTools\Insurance\BankProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class BankProviderTest extends BaseTestCase
{
    /**
     * @covers \InsuranceTools\Insurance\BankProvider::__construct()
     * @covers \InsuranceTools\Insurance\BankProvider::getQuote()
     */
    public function testGetQuote()
    {
        $this->testProviderDataSource(
            BankProvider::class,
            BankDataSourceInterface::class
        );
    }

    /**
     * @covers \InsuranceTools\Insurance\BankProvider::__construct()
     * @covers \InsuranceTools\Insurance\BankProvider::getQuote()
     */
    public function testBankBadDataException()
    {
        $this->testBadDataException(
            BankProvider::class,
            BankDataSourceInterface::class
        );
    }

    /**
     * @covers \InsuranceTools\Insurance\BankProvider::__construct()
     * @covers \InsuranceTools\Insurance\BankProvider::getId()
     */
    public function testGetId()
    {
        /** @var BankDataSourceInterface|MockObject $client */
        $client = $this->getMockBuilder(BankDataSourceInterface::class)
            ->onlyMethods(['getQuote'])
            ->getMock()
        ;

        /** @var DecoderInterface|MockObject $decoder */
        $decoder = $this->getMockBuilder(DecoderInterface::class)
            ->onlyMethods(
                [
                    'decode',
                    'supportsDecoding',
                ]
            )
            ->getMock()
        ;

        $provider = new BankProvider($client, $decoder);
        $id = $provider->getId();

        $this->assertEquals('bank', $id);
    }
}
