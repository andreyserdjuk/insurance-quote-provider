<?php

namespace InsuranceTools\Tests\Unit;

use InsuranceTools\Insurance\InsuranceCompanyProvider;
use InsuranceTools\Insurance\InsuranceCompanyDataSourceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class InsuranceCompanyProviderTest extends BaseTestCase
{
    /**
     * @covers \InsuranceTools\Insurance\InsuranceCompanyProvider::__construct()
     * @covers \InsuranceTools\Insurance\InsuranceCompanyProvider::getQuote()
     */
    public function testGetQuote()
    {
        $this->testProviderDataSource(
            InsuranceCompanyProvider::class,
            InsuranceCompanyDataSourceInterface::class
        );
    }

    /**
     * @covers \InsuranceTools\Insurance\InsuranceCompanyProvider::__construct()
     * @covers \InsuranceTools\Insurance\InsuranceCompanyProvider::getQuote()
     */
    public function testBankBadDataException()
    {
        $this->testBadDataException(
            InsuranceCompanyProvider::class,
            InsuranceCompanyDataSourceInterface::class
        );
    }

    /**
     * @covers \InsuranceTools\Insurance\InsuranceCompanyProvider::__construct()
     * @covers \InsuranceTools\Insurance\InsuranceCompanyProvider::getId()
     */
    public function testGetId()
    {
        /** @var InsuranceCompanyDataSourceInterface|MockObject $client */
        $client = $this->getMockBuilder(InsuranceCompanyDataSourceInterface::class)
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

        $provider = new InsuranceCompanyProvider($client, $decoder);
        $id = $provider->getId();

        $this->assertEquals('insurance-company', $id);
    }
}
