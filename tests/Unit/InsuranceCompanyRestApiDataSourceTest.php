<?php

namespace InsuranceTools\Tests\Unit;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use InsuranceTools\Insurance\Exception\ProviderDataRetrievingException;
use InsuranceTools\Insurance\InsuranceCompanyRestApiDataSource;
use PHPUnit\Framework\MockObject\MockObject;

class InsuranceCompanyRestApiDataSourceTest extends BaseTestCase
{
    /**
     * @covers \InsuranceTools\Insurance\AbstractApiDataSource::__construct()
     * @covers \InsuranceTools\Insurance\InsuranceCompanyRestApiDataSource::getQuote()
     */
    public function testGetQuote()
    {
        $rootUrl = 'https://examplehost.local/';
        $expectedRequestUri = 'https://examplehost.local/insurance';
        $expectedRequestMethod = 'POST';
        $expectedResponseData = 'response data';
        $expectedPostData = [
            'form_params' => [
                'month' => 3,
            ]
        ];

        /** @var ClientInterface $client */
        $client = $this->mockHttpClient(
            $expectedResponseData,
            $this->equalTo($expectedRequestMethod),
            $this->equalTo($expectedRequestUri),
            $this->equalTo($expectedPostData)
        );

        $apiClient = new InsuranceCompanyRestApiDataSource($rootUrl, $client);
        $responseData = $apiClient->getQuote();

        $this->assertEquals($expectedResponseData, $responseData);
    }

    /**
     * @covers \InsuranceTools\Insurance\AbstractApiDataSource::__construct()
     * @covers \InsuranceTools\Insurance\InsuranceCompanyRestApiDataSource::getQuote()
     */
    public function testClientException()
    {
        $this->expectException(ProviderDataRetrievingException::class);

        /** @var MockObject|ClientInterface $client */
        $client = $this->mockGuzzleClient();
        $client->method('request')
            ->willThrowException(new TransferException());

        $apiClient = new InsuranceCompanyRestApiDataSource('', $client);
        $apiClient->getQuote();
    }
}
