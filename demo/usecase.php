<?php

use GuzzleHttp\Client;
use InsuranceTools\Insurance\BankProvider;
use InsuranceTools\Insurance\BankRestApiDataSource;
use InsuranceTools\Insurance\Exception\ExceptionInterface;
use InsuranceTools\Insurance\Exception\ProviderBadDataException;
use InsuranceTools\Insurance\Exception\ProviderDataRetrievingException;
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

try {
    $quotas = $providerAggregate->getQuote();
} catch (ProviderBadDataException $e) {
    error_log(sprintf(
        'Provider got malformed data, error message: "%s"',
        $e->getMessage()
    ));
} catch (ProviderDataRetrievingException $e) {
    error_log(sprintf(
        'Provider cannot connect to source, error message: "%s"',
        $e->getMessage()
    ));
} catch (ExceptionInterface $e) {
    error_log($e->getMessage());
}
