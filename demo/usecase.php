<?php

use GuzzleHttp\Client;
use InsuranceTools\Insurance\BankProvider;
use InsuranceTools\Insurance\BankRestApiDataSource;
use InsuranceTools\Insurance\InsuranceCompanyProvider;
use InsuranceTools\Insurance\InsuranceCompanyRestApiDataSource;
use InsuranceTools\Insurance\ProviderAggregate;
use Symfony\Component\Serializer\Encoder\JsonDecode;

require __DIR__ . '/../vendor/autoload.php';

$bankRootUrl = 'http://example-bank-api.local';
$insuranceCompanyRootUrl = 'http://example-insurance-company.local';

$client = new Client();
$decoder = new JsonDecode();

$bankDataSource = new BankRestApiDataSource($bankRootUrl, $client);
$insuranceCompanyDataSource = new InsuranceCompanyRestApiDataSource($insuranceCompanyRootUrl, $client);

$bankProvider = new BankProvider($bankDataSource, $decoder);
$insuranceCompanyProvider = new InsuranceCompanyProvider($insuranceCompanyDataSource, $decoder);

$providerAggregate = new ProviderAggregate([$bankProvider, $insuranceCompanyProvider]);
$quotas = $providerAggregate->getQuote();
