<?php

namespace Viviniko\Currency\Contracts;

interface CurrencyService
{
    public function setDefault($code);

    public function getDefault();
}