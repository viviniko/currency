<?php

namespace Viviniko\Currency;

use Viviniko\Currency\Facades\Currency;

class Amount
{
    /**
     * @var string
     */
    public $currency;

    /**
     * @var float|int
     */
    public $value;

    /**
     * Amount constructor.
     * @param $currency
     * @param $value
     */
    public function __construct($currency, $value)
    {
        $this->currency = $currency;
        $this->value = $value;
    }

    public static function createBaseAmount($value)
    {
        return new static(Currency::getBase()->code, $value);
    }

    public function discount($discount)
    {
        return new static($this->currency, $this->value * (1 - $discount / 100));
    }

    public function add(Amount $amount)
    {
        return new static($this->currency, $this->value + $amount->changeCurrency($this->currency)->value);
    }

    public function sub(Amount $amount)
    {
        return new static($this->currency, max(0, $this->value - $amount->changeCurrency($this->currency)->value));
    }

    public function mul($value)
    {
        return new static($this->currency, $this->value * $value);
    }

    public function div($value)
    {
        return new static($this->currency, $this->value / $value);
    }

    public function changeCurrency($currency)
    {
        $value = $this->value;
        if ($currency != $this->currency) {
            $targetCurrency = Currency::getCurrencyByCode($currency);
            $sourceCurrency = Currency::getCurrencyByCode($this->currency);
            $value = $this->value * $targetCurrency->rate / $sourceCurrency->rate;
        }

        return new static($currency, $value);
    }

    public function __toString()
    {
        return Currency::getCurrencyByCode($this->currency)->symbol .
            number_format($this->value, 2, '.',  ',');
    }

}