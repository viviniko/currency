<?php

namespace Viviniko\Currency\Repositories\Currency;

interface CurrencyRepository
{
    public function first();

    public function all();

    public function findByCode($code);
}