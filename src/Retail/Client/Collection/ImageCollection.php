<?php


namespace DealPerch\Retail\Client\Collection;


use DealPerch\Retail\Client\DTO\Image;

final class ImageCollection implements \Iterator, \JsonSerializable, \Countable
{
    private $data;

    public function __construct(array $values)
    {
        $this->data = (function (Image ...$values) {
            return $values;
        }) (...$values);
    }

    /**
     * @return Image|boolean
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
         * @var Image $datum
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