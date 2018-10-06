<?php

namespace Viviniko\Currency\Services;

interface CurrencyService
{
    public function setDefault($code);

    public function getDefault();

    public function listCurrencies($name = 'name', $key = 'code');
}