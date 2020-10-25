<?php

declare(strict_types=1);

namespace InsuranceTools\Insurance;

use InsuranceTools\Insurance\Exception\ProviderDataRetrievingException;
use Psr\Http\Client\ClientExceptionInterface;

class InsuranceCompanyRestApiDataSource extends AbstractApiDataSource implements InsuranceCompanyDataSourceInterface
{
    /**
     * @inheritDoc
     */
    public function getQuote(): string
    {
        try {
            return $this->client->request(
                'POST',
                $this->rootUrl . '/insurance',
                [
                    'form_params' => [
                        'month' => 3,
                    ]
                ]
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
