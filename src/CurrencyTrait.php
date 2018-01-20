<?php

namespace Viviniko\Currency;

use Viviniko\Currency\Facades\Currency;

trait CurrencyTrait
{
    public function getCurrencyAttribute()
    {
        return Currency::s();
    }

    public function getOriginPriceAttribute()
    {
        return $this->getOriginal('price');
    }

    public function getOriginMarketPriceAttribute()
    {
        return $this->getOriginal('market_price');
    }

    public function getPriceAttribute($value)
    {
        return Currency::t($value);
    }

    public function getMarketPriceAttribute($value)
    {
        return Currency::t($value);
    }
}