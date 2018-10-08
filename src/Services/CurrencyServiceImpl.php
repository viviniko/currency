<?php

namespace Viviniko\Currency\Services\Currency;

use Viviniko\Currency\Amount;
use Viviniko\Currency\Repositories\Currency\CurrencyRepository;
use Viviniko\Currency\Services\CurrencyService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class CurrencyServiceImpl implements CurrencyService
{
    protected $currencyRepository;

    protected $currencies;

    protected $events;

    protected $base;

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

    /**
     * {@inheritdoc}
     */
    public function getBase()
    {
        if (!$this->base) {
            $currency = $this->getCurrencies()->where('rate', 1);
            throw_if(!$currency, new \LogicException());
            $this->base = $currency;
        }

        return $this->base;
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

    /**
     * {@inheritdoc}
     */
    public function getCurrencyByCode($code)
    {
        return $this->getCurrencies()->where('code', $code)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencies()
    {
        if (!$this->currencies) {
            $this->currencies = Cache::remember('currency.currencies', Config::get('cache.ttl', 10), function () {
                return $this->currencyRepository->all();
            });
        }

        return $this->currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function createCurrency(array $data)
    {
        $result = $this->currencyRepository->create($data);
        Cache::forget('currency.currencies');

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function updateCurrency($id, array $data)
    {
        $result = $this->currencyRepository->update($id, $data);
        Cache::forget('currency.currencies');

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteCurrency($id)
    {
        $result = $this->currencyRepository->delete($id);
        Cache::forget('currency.currencies');

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function listCurrencies($name = 'name', $key = 'code')
    {
        return $this->getCurrencies()->pluck($name, $key);
    }
}