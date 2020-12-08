<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PriceService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPrice(string $coin): ?string
    {
        $path = sprintf('https://api.coingecko.com/api/v3/coins/%s', $coin);

        try {
            $response = $this->client->request('GET', $path);
            $content = $response->toArray();
        } catch (\Exception $exception) {
            return null;
        }

        $price = null;
        if (array_key_exists('market_data', $content)) {
            $price = $content['market_data']['current_price']['eur'];
        }

        return null === $price ? null : sprintf('€ %s', $price);
    }
}
