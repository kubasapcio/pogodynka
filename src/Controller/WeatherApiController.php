<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;


final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            if($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'country' => $country,
                    'city' => $city,
                    'measurements' => $measurements,
                ], new Response('', Response::HTTP_OK, ['Content-Type' => 'text/plain; charset=utf-8']) );
            }
            return $this->render('weather_api/index.json.twig', [
                'country' => $country,
                'city' => $city,
                'measurements' => $measurements,
            ], new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json; charset=utf-8']));
        }

        if ($format === 'csv') {
            $lines = ["city,country,date,celsius,fahrenheit"];
            foreach ($measurements as $m) {
                $lines[] = sprintf(
                    '%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $m->getDate()->format('Y-m-d'),
                    $m->getCelsius(),
                    $m->getFahrenheit(),
                );
            }

            return new Response(
                implode("\n", $lines),
                Response::HTTP_OK,
                ['Content-Type' => 'text/plain; charset=utf-8']
            );
        }

        return $this->json([
            'country' => $country,
            'city' => $city,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}
