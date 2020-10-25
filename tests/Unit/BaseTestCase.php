<?php

namespace InsuranceTools\Tests\Unit;

use GuzzleHttp\ClientInterface;
use InsuranceTools\Insurance\BankDataSourceInterface;
use InsuranceTools\Insurance\Exception\ProviderBadDataException;
use InsuranceTools\Insurance\InsuranceCompanyDataSourceInterface;
use InsuranceTools\Insurance\ProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

abstract class BaseTestCase extends TestCase
{
    protected function mockHttpClient(string $responseData, ...$requestExpectation): MockObject
    {
        $client = $this->mockGuzzleClient();

        $bodyStream = $this->createConfiguredMock(
            StreamInterface::class,
            [
                'getContents' => $responseData,
            ]
        );

        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getBody' => $bodyStream,
            ]
        );

        $client->expects($this->once())
            ->method('request')
            ->with(...$requestExpectation)
            ->willReturn($response)
        ;

        return $client;
    }

    /**
     * @param string $class implements ProviderInterface
     * @param string $dataSourceInterface extends ProviderDataSourceInterface
     */
    protected function testProviderDataSource(string $class, string $dataSourceInterface)
    {
        $expectedQuoteData = '[{"quote": "1.00"}]';
        $expectedDecodedData = [['quote' => '1.00']];

        /** @var BankDataSourceInterface|InsuranceCompanyDataSourceInterface|MockObject $client */
        $client = $this->getMockBuilder($dataSourceInterface)
            ->onlyMethods(['getQuote'])
            ->getMock()
        ;

        $client->expects($this->once())
            ->method('getQuote')
            ->willReturn($expectedQuoteData)
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

        $decoder->expects($this->once())
            ->method('decode')
            ->with(
                $expectedQuoteData,
                'json'
            )
            ->willReturn($expectedDecodedData)
        ;

        /** @var ProviderInterface $provider */
        $provider = new $class($client, $decoder);
        $quote = $provider->getQuote();

        $this->assertEquals($expectedDecodedData, $quote);
    }

    protected function testBadDataException(string $class, string $dataSourceInterface)
    {
        $this->expectException(ProviderBadDataException::class);

        $expectedQuoteData = '[{"quote": "1.00"}]';

        /** @var BankDataSourceInterface|InsuranceCompanyDataSourceInterface|MockObject $client */
        $client = $this->getMockBuilder($dataSourceInterface)
            ->onlyMethods(['getQuote'])
            ->getMock()
        ;

        $client->expects($this->once())
            ->method('getQuote')
            ->willReturn($expectedQuoteData)
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

        $decoder->expects($this->once())
            ->method('decode')
            ->with(
                $expectedQuoteData,
                'json'
            )
            ->willThrowException(new UnexpectedValueException())
        ;

        /** @var ProviderInterface $provider */
        $provider = new $class($client, $decoder);
        $provider->getQuote();
    }

    /**
     * @return MockObject|ClientInterface
     */
    protected function mockGuzzleClient()
    {
        return $this->getMockBuilder(ClientInterface::class)
            ->onlyMethods(
                [
                    'send',
                    'sendAsync',
                    'request',
                    'requestAsync',
                    'getConfig',
                ]
            )
            ->getMock()
        ;
    }
}
