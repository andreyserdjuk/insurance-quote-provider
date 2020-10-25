<?php
declare(strict_types=1);

namespace InsuranceTools\Insurance;

use InsuranceTools\Insurance\Exception\ProviderDataRetrievingException;

/**
 * Unifies insurance provider data source.
 */
interface ProviderDataSourceInterface
{
    /**
     * @throws ProviderDataRetrievingException
     */
    public function getQuote(): string;
}
