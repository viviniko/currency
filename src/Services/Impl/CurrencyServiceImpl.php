<?php

namespace Viviniko\Currency\Services\Currency;

use Viviniko\Currency\Contracts\CurrencyService;
use Viviniko\Currency\Repositories\Currency\CurrencyRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class CurrencyServiceImpl implements CurrencyService
{
    protected static $enabled = true;

    protected static $autoConvert = false;

    protected $currencyRepository;

    protected $currencies;

    protected $events;

    protected $default;

    protected $current;

    /**
     * CurrencyServiceImpl constructor.
     * @param CurrencyRepository $currencyRepository
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function __construct(CurrencyRepository $currencyRepository, Dispatcher $events)
    {
        $this->currencyRepository = $currencyRepository;
        $this->events = $events;
    }

    public function setDefault($code)
    {
        $this->default = $code;

        return $this;
    }

    public function getDefault()
    {
        if (!$this->default) {
            $this->default = data_get($this->getCurrencies()->first(), 'code', 'USD');
        }

        return $this->default;
    }

    /**
     * Get currency code or set current currency code
     *
     * @param mixed $code
     * @return mixed
     */
    public function current($code = null)
    {
        if (!$code) {
            if (!$this->current) {
                if (!$this->current(Cookie::get('currency') ?? $this->getDefault())) {
                    if (($cookie = Cookie::get('currency')) && !$this->findByCode($cookie)) {
                        $this->current($this->getDefault());
                    } else {
                        $this->setDefault(null)->current();
                    }
                }
            }
            return $this->current;
        }

        if (($currency = $this->findByCode($code)) && data_get($this->current, 'code') != $currency->code) {
            $this->current = $currency;
            if (Cookie::get('currency') != data_get($this->current, 'code')) {
                Cookie::queue(Cookie::forever('currency', $code));
                $this->events->fire('currency.changed');
            }
            return true;
        }

        return false;
    }

    public function lists()
    {
        return $this->getCurrencies()->mapWithKeys(function ($currency) {
            return [strtolower($currency->code) => $currency];
        });
    }

    public function e($price, $symbol = true)
    {
        if (!static::$enabled) return $price;

        $currency = '';
        if (static::$autoConvert && is_numeric($price) && ($current = $this->current())) {
            $price = $price * $current->rate;
            $currency = $symbol ? $current->symbol : '';
        }

        return is_numeric($price) ? ($currency . $this->format($price, $symbol ? ',' : '')) : $price;
    }

    public function toUSD($price, $from = null)
    {
        $currency = $from ? $this->findByCode($from) : $this->current();
        if ($currency && is_numeric($price) && strtoupper($currency->code) != 'USD') {
            $price = $this->f($price / $currency->rate);
        }

        return $price;
    }

    public function f($price)
    {
        return $this->format($price, '');
    }

    public function h($price)
    {
        return $this->format($price, ',');
    }

    public function s($default = '$')
    {
        return data_get($this->current, 'symbol', $default);
    }

    public function t($price)
    {
        return $this->e($price, false);
    }

    public function format($price, $thousandsSep = '')
    {
        return number_format($price, 2, '.', $thousandsSep);
    }

    public function findByCode($code)
    {
        return $this->getCurrencies()->where('code', $code)->first();
    }

    public function getCurrencies()
    {
        if (!$this->currencies) {
            $this->currencies = Cache::remember('currency.currencies', Config::get('cache.ttl', 10), function () {
                return $this->currencyRepository->all();
            });
        }

        return $this->currencies;
    }

    public function setCurrencies($currencies)
    {
        $this->currencies = $currencies;

        return $this;
    }

    /**
     * @param bool $convert
     */
    public static function setAutoConvert($convert)
    {
        static::$autoConvert = $convert;
    }

    /**
     * @param bool $enable
     */
    public static function enable($enable)
    {
        static::$enabled = $enable;
    }

    public function createCurrency(array $data)
    {
        return $this->currencyRepository->create($data);
    }

    public function updateCurrency($id, array $data)
    {
        return $this->currencyRepository->update($id, $data);
    }

    public function deleteCurrency($id)
    {
        return $this->currencyRepository->delete($id);
    }
}