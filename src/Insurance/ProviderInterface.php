<?php

declare(strict_types=1);

namespace InsuranceTools\Insurance;

use InsuranceTools\Insurance\Exception\ProviderIntegrationException;

interface ProviderInterface
{
    /**
     * @throws ProviderIntegrationException
     */
    public function getQuote(): iterable;
}
