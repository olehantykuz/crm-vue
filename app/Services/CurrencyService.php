<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\CurrencyConversation;
use App\Repositories\CurrencyRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected $accessKey;
    /** @var string  */
    protected $baseCurrency;
    protected $baseUrl;
    /** @var CurrencyRepository  */
    protected $currencyRepository;

    /**
     * CurrencyService constructor.
     */
    public function __construct()
    {
        $this->accessKey = config('services.fixer.access_key');
        $this->baseCurrency = config('app.default_currency');
        $this->baseUrl = 'http://data.fixer.io/api';
        $this->currencyRepository = new CurrencyRepository();
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
     * @return string
     */
    public function getDefaultCurrencyCode()
    {
        return $this->baseCurrency;
    }

    /**
     * @return Currency[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Currency::all();
    }

    /**
     * @param int $id
     * @return Currency|null
     */
    public function getById(int $id)
    {
        return Currency::find($id);
    }

    /**
     * @return array
     */
    public function getCurrencyCodes()
    {
        return $this->getAll()
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
        $latestConversation = $currency->latestConversation();
        $now = Carbon::now();

        if ($latestConversation && ($latestConversation->created_at->day === $now->day)) {
            $currency->latestConversation()
                ->update(['rate' => $rate]);

            return $currency;
        }

        $instance = new CurrencyConversation();
        $instance->rate = $rate;

        $currency->conversation()
            ->save($instance);

        return $currency;
    }

    /**
     * @param string $date
     * @return Collection
     */
    public function getRelevantConversations(string $date)
    {
        $nearestDate = $this->currencyRepository->getNearestConversationDateByDate($date)
            ?? $this->currencyRepository->getLatestConversationDate();

        return $nearestDate ? $this->currencyRepository->getConversationsByDate($nearestDate) : collect([]);
    }
}
