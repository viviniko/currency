<?php

namespace Viviniko\Currency\Models;

use Viviniko\Support\Database\Eloquent\Model;

/**
 * @property mixed $category_text
 */
class Currency extends Model
{
    protected $tableConfigKey = 'currency.currencies_table';

    protected $fillable = ['name', 'code', 'symbol', 'rate'];

}