<?php

declare(strict_types=1);

namespace InsuranceTools\Insurance;

use InsuranceTools\Insurance\Exception\InvalidArgumentException;

class ProviderAggregate implements ProviderInterface
{
    /**
     * @var UniqueProviderInterface[]
     */
    private array $providers;

    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function getQuote(): iterable
    {
        foreach ($this->providers as $provider) {
            yield $provider->getId() => $provider->getQuote();
        }
    }

    private function addProvider(UniqueProviderInterface $provider): void
    {
        if (isset($this->providers[$provider->getId()])) {
            throw new InvalidArgumentException('Non unique provider id.');
        }

        $this->providers[$provider->getId()] = $provider;
    }
}
