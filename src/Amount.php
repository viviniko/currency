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
    public $amount;

    /**
     * Amount constructor.
     * @param $currency
     * @param $amount
     * @throws \Throwable
     */
    public function __construct($currency, $amount)
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public static function createBaseAmount($amount)
    {
        return new static(Currency::getBase()->code, $amount);
    }

    public function discount($discount)
    {
        return new static($this->currency, $this->amount * (1 - $discount / 100));
    }

    public function add(Amount $amount)
    {
        return new static($this->currency, $this->amount + $amount->changeCurrency($this->currency)->amount);
    }

    public function sub(Amount $amount)
    {
        return new static($this->currency, max(0, $this->amount - $amount->changeCurrency($this->currency)->amount));
    }

    public function mul($value)
    {
        return new static($this->currency, $this->amount * $value);
    }

    public function div($value)
    {
        return new static($this->currency, $this->amount / $value);
    }

    public function changeCurrency($currency)
    {
        $amount = $this->amount;
        if ($currency != $this->currency) {
            $targetCurrency = Currency::getCurrencyByCode($currency);
            $sourceCurrency = Currency::getCurrencyByCode($this->currency);
            $amount = $this->amount * $targetCurrency->rate / $sourceCurrency->rate;
        }

        return new static($amount, $currency);
    }

    public function __toString()
    {
        return Currency::getCurrencyByCode($this->currency)->symbol .
            number_format($this->amount, 2, '.',  ',');
    }

}