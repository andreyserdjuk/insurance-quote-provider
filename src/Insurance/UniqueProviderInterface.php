<?php

namespace InsuranceTools\Insurance;

interface UniqueProviderInterface extends ProviderInterface
{
    public function getId(): string;
}
