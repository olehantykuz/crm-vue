<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\CurrencyConversation;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected $accessKey;
    protected $baseCurrency;
    protected $baseUrl;

    /**
     * CurrencyService constructor.
     */
    public function __construct()
    {
        $this->accessKey = config('services.fixer.access_key');
        $this->baseCurrency = config('app.default_currency');
        $this->baseUrl = 'http://data.fixer.io/api';
    }

    /**
     * @param array $currencyCodes
     * @return string
     */
    protected function getLatestConversationUrl(array $currencyCodes)
    {
        $url = $this->baseUrl . '/latest?';
        $queryParams = [
            'access_key' => $this->accessKey,
            'base' => $this->baseCurrency,
            'symbols' => implode(',', $currencyCodes),
        ];

        return $url . http_build_query($queryParams);
    }

    /**
     * @return array
     */
    public function getConversationRates()
    {
        $result = [];
        $response = Http::get($this->getLatestConversationUrl($this->getCurrencyCodes()));

        if ($response->ok()) {
            $data = $response->json();
            if ($data['success']) {
                $result = $data['rates'];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getCurrencyCodes()
    {
        return Currency::all()
            ->pluck('code')
            ->toArray();
    }

    /**
     * @param string $code
     * @return Currency|null
     */
    public function getCurrencyByCode(string $code)
    {
        return Currency::where('code', $code)
            ->first();
    }

    /**
     * @param Currency $currency
     * @param int|float $rate
     * @return Currency
     */
    public function updateConversation(Currency $currency, $rate)
    {
        if ($currency->conversation) {
            $currency->conversation
                ->update(['rate' => $rate]);

            return $currency;
        }

        $instance = new CurrencyConversation();
        $instance->rate = $rate;

        $currency->conversation()
            ->save($instance);

        return $currency;
    }
}
