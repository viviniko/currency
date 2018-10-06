<?php

namespace Viviniko\Currency;

use Viviniko\Currency\Facades\CurrencyFacade;
use Viviniko\Currency\Models\Currency;

class Amount
{
    private $currency;

    private $amount;

    public function __construct($currency, $amount)
    {
        if (is_string($currency)) {
            $currency = CurrencyFacade::getCurrencyByCode($currency);
        }
        throw_if(!$currency instanceof Currency, new \InvalidArgumentException());
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public function __toString()
    {
        return $this->currency->symbol . $this->amount;
    }

}