<?php
declare(strict_types=1);

namespace DealPerch\Retail\Client\DTO;

use Money\Currency;
use Money\Money;

final class Price implements \JsonSerializable
{

    const CUR_USD = 'USD';

    const CUR_CAD = 'CAD';

    const INVALID_CURRENCY_CODE_EXC_CODE = 2;

    const INVALID_CURRENCY_CODE_EXC_MESSAGE = 'An invalid currency code was provided.';

    protected $validCurrencies = [
        0 => self::CUR_USD,
        1 => self::CUR_CAD
    ];


    private $amount;


    private $currencyCode;

    /**
     *
     * @param float $amount
     * @param string $currencyCode
     * @throws \Exception
     */
    public function __construct(float $amount, string $currencyCode)
    {
        if (!in_array($currencyCode, $this->validCurrencies)) {
            throw new \Exception(self::INVALID_CURRENCY_CODE_EXC_MESSAGE, self::INVALID_CURRENCY_CODE_EXC_CODE);
        }

        $this->currencyCode = $currencyCode;
        $this->amount = (int)$amount;
    }


    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currencyCode' => $this->getCurrencyCode()
        ];
    }

    public static function fromMoney(Money $money): Price
    {
        return new Price($money->getAmount(), $money->getCurrency()->getName());

    }

    public function toMoney(): Money
    {
        return new Money($this->amount / 100, new Currency($this->currencyCode));
    }

    public function jsonSerialize()
    {
        return $this->toArray();

    }

    public static function fromArray(array $priceAsArr): Price
    {
        return new Price((float)($priceAsArr['amount'] ?? 0), (string)($priceAsArr['currencyCode'] ?? 'USD'));
    }


}