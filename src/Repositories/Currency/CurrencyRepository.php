<?php

namespace Viviniko\Currency\Repositories\Currency;

interface CurrencyRepository
{
    public function first();

    public function all();

    public function findByCode($code);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}