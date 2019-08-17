<?php

namespace Viviniko\Currency;

use Viviniko\Currency\Facades\Currency;

class Money
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
     */
    public function __construct($currency, $amount)
    {
        $this->currency = $currency;
        $this->amount = number_format($amount, 2, '.',  '');
    }

    public static function create($amount = 0, $currency = null)
    {
        return new static($currency ?: Currency::getBase()->code, $amount);
    }

    public function discount($discount)
    {
        return new static($this->currency, $this->amount * (1 - $discount / 100));
    }

    public function add(Money $money)
    {
        return new static($this->currency, $this->amount + $money->changeCurrency($this->currency)->amount);
    }

    public function sub(Money $money)
    {
        return new static($this->currency, max(0, $this->amount - $money->changeCurrency($this->currency)->amount));
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

        return new static($currency, $amount);
    }

    public function orElse($else)
    {
        return $this->isValid() ? $this : $else;
    }

    public function isValid()
    {
        return is_numeric($this->amount) && $this->amount > 0;
    }

    public function __toString()
    {
        return Currency::getCurrencyByCode($this->currency)->symbol .
            number_format($this->amount, 2, '.',  ',');
    }

}