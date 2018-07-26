<?php
/**
 * Created by PhpStorm.
 * User: jackp
 * Date: 3/19/2018
 * Time: 5:32 PM
 */

namespace DealPerch\Retail\Client\DTO\SearchResponse;


use DealPerch\Retail\ValueObject\DistanceCalculation;

/**
 * Class Supplier
 * @package DealPerch\Retail\DTO\Search
 * @SWG\Definition(definition="DTO/Search/Supplier")
 */
class Supplier implements \JsonSerializable
{

    const SUPPLIER_TYPES = [
        self::ONLINE_ONLY => self::ONLINE_ONLY,
        self::LOCAL_ONLY => self::LOCAL_ONLY,
        self::LOCAL_AND_ONLINE => self::LOCAL_AND_ONLINE
    ];

    const LOCAL_ONLY = 'local_only';

    const LOCAL_AND_ONLINE = 'local_and_online';

    const ONLINE_ONLY = 'online_only';

    /**
     * @var string
     * @SWG\Property()
     */
    private $id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $displayName;

    /**
     * @var null|string
     * @SWG\Property()
     */
    private $logoURL;

    /**
     * @var DistanceCalculation|null
     * @SWG\Property(type="object", ref="#/definitions/Traits~1DistanceCalculation")
     *
     */
    private $distance;

    /**
     * @var string
     * @SWG\Property(example={"local_only, local_and_online, online_only"})
     */
    private $supplierType;

    public function __construct(
        string $id,
        string $displayName,
        ?string $logoURL,
        ?DistanceCalculation $distanceCalculation = null,
        string $supplierType = self::LOCAL_AND_ONLINE
    )
    {
        $this->id = $id;
        $this->displayName = $displayName;
        $this->logoURL = $logoURL;
        $this->distance = $distanceCalculation;
        if (!in_array($supplierType, self::SUPPLIER_TYPES)) {
            throw new \InvalidArgumentException('provided supplier type is not one of the following: ' . json_encode(self::SUPPLIER_TYPES));
        }
        $this->supplierType = $supplierType;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getLogoURL(): ?string
    {
        return $this->logoURL;
    }

    public static function fromArray(?array $input): ?Supplier
    {
        if (!is_array($input)) {
            return null;
        }

        return new Supplier($input['id'] ?? '', $input['displayName'] ?? '', $input['logoURL'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'displayName' => $this->getDisplayName(),
            'logoURL' => $this->getLogoURL(),
            'distance' => $this->distance,
            'location' => ($this->distance instanceof DistanceCalculation ? $this->distance->getEndLocation()->toArray() : null),
            'supplier_type' => $this->supplierType
        ];
    }


}