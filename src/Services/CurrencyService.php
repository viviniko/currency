<?php

namespace Viviniko\Currency\Services;

interface CurrencyService
{
    public function setDefault($code);

    public function getDefault();

    public function getCurrencies();

    public function listCurrencies($name = 'name', $key = 'code');

    public function createCurrency(array $data);

    public function updateCurrency($id, array $data);

    public function deleteCurrency($id);

    public function getCurrencyByCode($code);
}