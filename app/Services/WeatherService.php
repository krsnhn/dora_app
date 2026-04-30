<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    private ?string $apiKey;
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getWeather(string $location): ?array
    {
        $cacheKey = 'weather_' . md5($location);

        return Cache::remember($cacheKey, 3600, function () use ($location) {
            try {
                $response = Http::timeout(5)->get("{$this->baseUrl}/weather", [
                    'q' => $location,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'temperature' => round($data['main']['temp']),
                        'feels_like' => round($data['main']['feels_like']),
                        'condition' => $data['weather'][0]['main'],
                        'description' => $data['weather'][0]['description'],
                        'icon' => $data['weather'][0]['icon'],
                        'humidity' => $data['main']['humidity'],
                        'wind_speed' => round($data['wind']['speed'] * 3.6, 1), // m/s to km/h
                        'city' => $data['name'],
                        'country' => $data['sys']['country'],
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('Weather API failed: ' . $e->getMessage());
            }
            return null;
        });
    }
}
