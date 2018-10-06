<?php

namespace Viviniko\Currency\Enums;

class Currency
{
    const CNY = 'CNY';

    const USD = 'USD';

    const EUR = 'EUR';

    const GBP = 'GBP';

    const CAD = 'CAD';

    const AUD = 'AUD';

    public static function values()
    {
        $rf = new \ReflectionClass(__CLASS__);

        return $rf->getConstants();
    }
}