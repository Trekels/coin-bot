<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PriceService;
use BotMan\BotMan\BotMan;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BotManController
{
    private Botman $botMan;
    private PriceService $priceService;

    public function __construct(BotMan $botMan, PriceService $priceService)
    {
        $this->botMan = $botMan;
        $this->priceService = $priceService;
    }

    /**
     * @Route(path="/botman", name="app_botman")
     */
    public function __invoke(): Response
    {
        $this->botMan->hears('Give me the {coin} price', function (BotMan $botMan, string $coin) {
            $price = $this->priceService->getPrice($coin);

            if (null === $price) {
                $botMan->reply(sprintf('Failed to fetch info for %s', $coin));
            } else {
                $botMan->reply(sprintf('The current %s price is %s', $coin, $price));
            }
        });

        $this->botMan->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I don\'t understand!');
        });

        $this->botMan->listen();

        return new Response();
    }
}
