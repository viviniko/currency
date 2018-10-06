<?php

namespace Viviniko\Currency;

use Viviniko\Currency\Facades\CurrencyFacade;
use Viviniko\Currency\Models\Currency;

class Amount
{
    private $currency;

    private $amount;

    private $discount;

    public function __construct($currency, $amount, $discount = 0)
    {
        if (is_string($currency)) {
            $currency = CurrencyFacade::getCurrencyByCode($currency);
        }
        throw_if(!$currency instanceof Currency, new \InvalidArgumentException());
        $this->currency = $currency;
        $this->discount = $discount;
        $this->amount = $amount * (1 - $discount / 100);
    }

    public static function createDefaultAmount($amount, $discount = 0)
    {
        return new static(CurrencyFacade::getDefault(), $amount, $discount);
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function __toString()
    {
        return $this->currency->symbol . $this->amount;
    }

}