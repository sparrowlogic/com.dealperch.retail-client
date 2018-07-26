<?php


namespace DealPerch\Retail\Client\Collection\SearchResponse;


use DealPerch\Retail\Client\DTO\SearchResponse\Product;

final class ProductCollection implements \Iterator, \JsonSerializable, \Countable
{
    private $data;

    public function __construct(array $values)
    {
        $this->data = (function (Product ...$values) {
            return $values;
        }) (...$values);
    }

    /**
     * @return Product|boolean
     */
    public function current()
    {
        return current($this->data);
    }

    public function next()
    {
        $var = next($this->data);

        return $var;
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        $key = key($this->data);

        return ($key !== null && $key !== false);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function jsonSerialize()
    {
        $out = [];
        /**
         * @var ProductSearchResponseDTO $datum
         */
        foreach ($this->data as $datum) {
            array_push($out, $datum->jsonSerialize());
        }

        return $out;
    }

    public function count()
    {
        return (int)count($this->data);
    }
}