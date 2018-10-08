<?php

namespace Viviniko\Currency\Repositories\Currency;

interface CurrencyRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * @param array $data
     * @return Object|null
     */
    public function create(array $data);

    /**
     * @param $id
     * @param array $data
     * @return Object|null
     */
    public function update($id, array $data);

    /**
     * @param $id
     * @return bool
     */
    public function delete($id);
}