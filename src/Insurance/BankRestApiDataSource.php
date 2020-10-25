<?php

declare(strict_types=1);

namespace InsuranceTools\Insurance;

use InsuranceTools\Insurance\Exception\ProviderDataRetrievingException;
use Psr\Http\Client\ClientExceptionInterface;

class BankRestApiDataSource extends AbstractApiDataSource implements BankDataSourceInterface
{
    /**
     * @inheritDoc
     */
    public function getQuote(): string
    {
        try {
            return $this->client->request(
                'GET',
                $this->rootUrl . '/bank'
            )
                ->getBody()
                ->getContents()
            ;
        } catch (ClientExceptionInterface $e) {
            throw new ProviderDataRetrievingException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
