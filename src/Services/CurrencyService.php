<?php

namespace Viviniko\Currency\Services;

interface CurrencyService
{
    /**
     * Get base currency. (rate eq 1)
     *
     * @return string
     */
    public function getBase();

    /**
     * Get currency.
     *
     * @param $id
     * @return mixed
     */
    public function getCurrency($id);

    /**
     * Get all currencies.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrencies();

    /**
     * Get list currencies.
     *
     * @param string $name
     * @param string $key
     * @return mixed
     */
    public function listCurrencies($name = 'name', $key = 'code');

    /**
     * Create new currency.
     *
     * @param array $data
     * @return mixed
     */
    public function createCurrency(array $data);

    /**
     * Update the currency.
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateCurrency($id, array $data);

    /**
     * Delete currency.
     *
     * @param $id
     * @return mixed
     */
    public function deleteCurrency($id);

    /**
     * Get currency by given code.
     *
     * @param $code
     * @return mixed
     */
    public function getCurrencyByCode($code);
}