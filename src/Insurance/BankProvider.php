<?php
declare(strict_types=1);

namespace InsuranceTools\Insurance;

use InsuranceTools\Insurance\Exception\ProviderBadDataException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class BankProvider implements UniqueProviderInterface
{
    public const ID = 'bank';

    private BankDataSourceInterface $client;

    private DecoderInterface $decoder;

    public function __construct(BankDataSourceInterface $client, DecoderInterface $decoder)
    {
        $this->client = $client;
        $this->decoder = $decoder;
    }

    /**
     * @inheritDoc
     */
    public function getQuote(): iterable
    {
        $data = $this->client->getQuote();

        try {
            return $this->decoder->decode($data, 'json');
        } catch (ExceptionInterface $e) {
            throw new ProviderBadDataException(
                sprintf(
                    'Cannot parse data from provider "%s", error message: "%s"',
                    __CLASS__,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }

    public function getId(): string
    {
        return self::ID;
    }
}
