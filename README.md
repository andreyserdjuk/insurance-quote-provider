#### Refactoring subject
> The original file is `./original/file.php`  
> Below is the list of problems and recommendations for `Insurance` class.

#### Use case and FAQ
Example of usage is `./demo/usecase.php`. It shows only the logic of classes initialization.
Real usage of this component implies usage of Dependency Injection tool.
As it is a component, it should not provide any DI container, Console Application etc.

#### Installation
```bash
composer install
```

#### Run tests
```bash
php bin/phpunit
```

#### Compliance with best practices (for `./original/file.php`)
* Class `Insurance` and its use-case placed in the same file `file.php`.
It will prevent `PSR-4` autoloader from properly resolving it.

* A coding standard tool should be used, `PSR-12` standard is recommended.

* Class should be in a separate file `Insurance.php` and resolved by autoloader.

* To prevent unexpected type juggling and make code more type-safe strict mode should be enabled `declare(strict_types=1)`. 

* Use new array syntax `[]` instead of `array()`.

* Missed arguments and return type declaration of `Insurance::quote()`.

* Class should be documented.

* `count()` call should not be used as condition in `for` cycle.

* String literals usage makes providers select algorithm less readable. Class constants
can be used instead. 

* Providers usage algorithm can be simplified to one `foreach` cycle.  

* Class method `quote` is intended for public usage but doesn't implement any interface. It makes client code 
dependent on `Insurance`. So interface with `quote` method is required.

* Direct usage of file_get_contents() and cURL: 
    * file_get_contents() will return `false` and print warnings on server errors.
    cURL `curl_exec()` returns `false` for server errors.
    It also makes class non-testable because interaction with external service is hard to mock.
    So these functions are too low-level and should be replaced with any implementation
    of [PSR-18: HTTP Client](https://www.php-fig.org/psr/psr-18/) `Psr\Http\Client\ClientInterface`.
* Similar issue has `json_decode`, when returns `false` but `JsonException` will be thrown until specific
flag is passed. This case is not handled properly.
* Also 'insurance-company' data is json-decoded but 'bank' data is not. The return data should be uniform.

* Hardcoded URL's should be injected instead of usage of string literals.

* Usually method using for getting some data starting with 'get' or 'fetch' or 'load'. Current `quote` method name
is not descriptive.

* Method `quote` returns an array. It's better to `yield` data as soon as it's available to smooth the possible 
memory peak usage (instead of allocating memory for arrays).

#### Architecture improvement
* The `Insurance::quote()` should be decomposed because it has hardcoded interaction logic with two providers 
(it also violates of Single responsibility). These providers should implement interface like `PriceProviderInterface`
and injected to `Insurance`.

* `Insurance` shouldn't have any limitation of quantity of providers and should not stick to any specific provider.

* Also `Insurance` technically is going to just aggregate the insurance prices providers. It can be renamed
to `InsuranceProviderAggregate` which tells what the class does without looking into it. 

* `Insurance` does not have proper handling of exceptional cases when server returns error or malformed response.
Also it does not throw any exception for bad scenarios. As a result we will never know what was happened when we got
array like `['bank' => false, 'insurance-company' => false]`. So specific exceptions should be added, thrown and
handled properly.
