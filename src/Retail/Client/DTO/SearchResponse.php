<?php
declare(strict_types=1);

namespace DealPerch\Retail\Client\DTO;


use DealPerch\Retail\Client\Collection\SearchResponse\ProductCollection;
use DealPerch\Retail\Client\DTO\SearchResponse\Product;

final class SearchResponse implements \JsonSerializable
{

    private $internalResults;

    private $externalResults;

    private $summaryProductIdentifiers;


    public function __construct(ProductCollection $internalResults, ProductCollection $externalResults, array $summaryProductIdentifiers)
    {
        $this->internalResults = $internalResults;
        $this->externalResults = $externalResults;
        $this->summaryProductIdentifiers = $summaryProductIdentifiers;
    }

    public static function fromArray($input = null): ?SearchResponse
    {
        if (!is_array($input)) {
            return null;
        }


        //if (array_key_exists('internal_'))

        $internalResults = new ProductCollection(array_map(function (array $input): ?Product {
            return Product::fromArray($input);
        }, $input['internalResults']));

        $externalResults = new ProductCollection(array_map(function (array $input): ?Product {
            return Product::fromArray($input);
        }, $input['externalResults']));

        $summaryProductIdentifiers = [];

        return new SearchResponse($internalResults, $externalResults, $summaryProductIdentifiers);
    }

    /**
     * @return ProductCollection
     */
    public function getInternalResults(): ProductCollection
    {
        return $this->internalResults;
    }

    /**
     * @return ProductCollection
     */
    public function getExternalResults(): ProductCollection
    {
        return $this->externalResults;
    }

    /**
     * @return array
     */
    public function getSummaryProductIdentifiers(): array
    {
        return $this->summaryProductIdentifiers;
    }


    public function jsonSerialize(): array
    {
        return [
            'internalResults' => $this->internalResults->jsonSerialize(),
            'externalResults' => $this->externalResults->jsonSerialize(),
            'summaryProductIdentifiers' => $this->summaryProductIdentifiers
        ];
    }


}