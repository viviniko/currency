<?php

namespace Viviniko\Currency\Repositories;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\SimpleRepository;

class CurrencyRepositoryImpl extends SimpleRepository implements CurrencyRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('currency.currencies_table'));
    }
}