<?php


namespace DealPerch\Retail\Client\DTO\SearchResponse;


use DealPerch\Retail\Client\Collection\ImageCollection;
use DealPerch\Retail\Client\DTO\Price;
use Money\Currency;

final class Product implements \JsonSerializable
{

    /**
     * @var null|string
     */
    private $productIdentifier;

    /**
     * @var string
     */
    private $GTIN;

    /**
     * @var Supplier
     */
    private $supplierDTO;

    /**
     * @var null|string
     * @SWG\Property()
     */
    private $title;

    /**
     * @var Price
     * @SWG\Property(ref="#/definitions/Entity~1Embeddable~1Price")
     */
    private $listPrice;

    /**
     * @var Price
     * @SWG\Property(ref="#/definitions/Entity~1Embeddable~1Price")
     */
    private $sellPrice;

    /**
     * @var null|string
     * @SWG\Property()
     */
    private $description;

    /**
     * @var array
     * @SWG\Property(type="array", @SWG\Items(type="string"))
     */
    private $tags;

    /**
     * @var ImageCollection
     * @SWG\Property(
     *     type="array",
     *     @SWG\Items(type="object", ref="#/definitions/DTO~1ImageDTO")
     * )
     */
    private $imageDTOCollection;

    /**
     * @var string
     */
    private $viewURL;

    public function __construct(
        ?string $productIdentifier,
        ?string $title,
        ?string $GTIN,
        Price $listPrice,
        Price $sellPrice,
        ?string $description,
        array $tags = [],
        ImageCollection $imageDTOCollection,
        Supplier $supplier,
        string $viewURL

    )
    {
        $this->productIdentifier = $productIdentifier;
        $this->title = $title;
        $this->GTIN = $GTIN;
        $this->listPrice = $listPrice;
        $this->sellPrice = $sellPrice;
        $this->description = $description;
        $this->tags = $tags;
        $this->imageDTOCollection = $imageDTOCollection;
        $this->supplierDTO = $supplier;
        $this->viewURL = $viewURL;
    }

    /**
     * @return null|string
     */
    public function getProductIdentifier(): ?string
    {
        return $this->productIdentifier;
    }

    /**
     * @return string
     */
    public function getGTIN(): string
    {
        return $this->GTIN;
    }

    /**
     * @return Supplier
     */
    public function getSupplierDTO(): Supplier
    {
        return $this->supplierDTO;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return Price
     */
    public function getListPrice(): Price
    {
        return $this->listPrice;
    }

    /**
     * @return Price
     */
    public function getSellPrice(): Price
    {
        return $this->sellPrice;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return ImageCollection
     */
    public function getImageDTOCollection(): ImageCollection
    {
        return $this->imageDTOCollection;
    }

    /**
     * @return string
     */
    public function getViewURL(): string
    {
        return $this->viewURL;
    }


    public static function fromArray(?array $input): ?Product
    {

        if (is_null($input)) {
            return null;
        }

        $productIdentifier = $input['id'] ?? '';
        $title = $input['title'] ?? '';
        $GTIN = $input['GTIN'] ?? null;
        $listPrice = new Price($input['listPrice']['amount'] ?? 0, new Currency($input['listPrice']['currencyCode']));
        $sellPrice = new Price($input['sellPrice']['amount'] ?? 0, new Currency($input['sellPrice']['currencyCode']));
        $description = $input['description'] ?? '';
        $tags = [];
        $imageDTOCollection = new ImageCollection([]);
        $supplier = Supplier::fromArray($input['supplier']);
        $viewURL = $input['viewURL'];

        $obj = new Product($productIdentifier, $title, $GTIN, $listPrice, $sellPrice, $description, $tags,
            $imageDTOCollection, $supplier, $viewURL);

        return $obj;


    }

    public function jsonSerialize(): array
    {
        return [
            'productIdentifier' => $this->productIdentifier,
            'title' => $this->title,
            'GTIN' => $this->GTIN,
            'description' => $this->description,
            'listPrice' => $this->listPrice,
            'sellPrice' => $this->sellPrice,
            'images' => $this->imageDTOCollection->jsonSerialize(),
            'supplier' => $this->supplierDTO->jsonSerialize(),
            'viewURL' => $this->viewURL
        ];
    }


}