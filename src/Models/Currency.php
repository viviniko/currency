<?php

namespace Viviniko\Currency\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Currency extends Model
{
    protected $fillable = [
        'name', 'code', 'symbol', 'rate', 'is_active',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('currency.currencies_table');
    }

}