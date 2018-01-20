<?php

namespace Viviniko\Currency\Repositories\Currency;

use Viviniko\Repository\SimpleRepository;

class EloquentCurrency extends SimpleRepository implements CurrencyRepository
{
    protected $modelConfigKey = 'currency.currency';

    public function all()
    {
        return $this->createModel()->newQuery()->get();
    }

    public function findByCode($code)
    {
        return $this->createModel()->where('code', $code)->first();
    }

    public function first()
    {
        return $this->createModel()->newQuery()->orderBy('id')->first();
    }
}