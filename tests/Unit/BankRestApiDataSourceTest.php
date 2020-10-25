<?php

namespace InsuranceTools\Tests\Unit;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use InsuranceTools\Insurance\BankRestApiDataSource;
use InsuranceTools\Insurance\Exception\ProviderDataRetrievingException;
use PHPUnit\Framework\MockObject\MockObject;

class BankRestApiDataSourceTest extends BaseTestCase
{
    /**
     * @covers \InsuranceTools\Insurance\AbstractApiDataSource::__construct()
     * @covers \InsuranceTools\Insurance\BankRestApiDataSource::getQuote()
     */
    public function testGetQuote()
    {
        $rootUrl = 'https://examplehost.local/';
        $expectedRequestUri = 'https://examplehost.local/bank';
        $expectedRequestMethod = 'GET';
        $expectedResponseData = 'response data';

        /** @var ClientInterface $client */
        $client = $this->mockHttpClient(
            $expectedResponseData,
            $this->equalTo($expectedRequestMethod),
            $this->equalTo($expectedRequestUri)
        );

        $bankClient = new BankRestApiDataSource($rootUrl, $client);
        $responseData = $bankClient->getQuote();

        $this->assertEquals($expectedResponseData, $responseData);
    }

    /**
     * @covers \InsuranceTools\Insurance\AbstractApiDataSource::__construct()
     * @covers \InsuranceTools\Insurance\BankRestApiDataSource::getQuote()
     */
    public function testClientException()
    {
        $this->expectException(ProviderDataRetrievingException::class);

        /** @var MockObject|ClientInterface $client */
        $client = $this->mockGuzzleClient();
        $client->method('request')
            ->willThrowException(new TransferException());

        $apiClient = new BankRestApiDataSource('', $client);
        $apiClient->getQuote();
    }
}
