<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    private ?string $apiKey;
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5';
    protected string $oneCallUrl = 'https://api.openweathermap.org/data/3.0/onecall';

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

    public function getWeatherByCoordinates(float $latitude, float $longitude, ?string $label = null): ?array
    {
        $cacheKey = 'weather_coordinates_' . md5($latitude . ',' . $longitude);

        return Cache::remember($cacheKey, 3600, function () use ($latitude, $longitude, $label) {
            try {
                $response = Http::timeout(5)->get("{$this->baseUrl}/weather", [
                    'lat' => $latitude,
                    'lon' => $longitude,
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
                        'wind_speed' => round($data['wind']['speed'] * 3.6, 1),
                        'city' => $data['name'] ?: ($label ?: 'Destination'),
                        'country' => $data['sys']['country'] ?? '',
                        'source' => 'current-coordinates',
                    ];
                }

                \Log::warning('OpenWeather coordinate weather failed: ' . $response->body());
            } catch (\Exception $e) {
                \Log::warning('Coordinate weather API failed: ' . $e->getMessage());
            }

            return null;
        });
    }

    public function getOneCallWeather(float $latitude, float $longitude, ?string $label = null): ?array
    {
        $cacheKey = 'onecall_weather_' . md5($latitude . ',' . $longitude);

        return Cache::remember($cacheKey, 3600, function () use ($latitude, $longitude, $label) {
            try {
                $response = Http::timeout(8)->get($this->oneCallUrl, [
                    'lat' => $latitude,
                    'lon' => $longitude,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'exclude' => 'minutely',
                ]);

                if (!$response->successful()) {
                    \Log::warning('OpenWeather One Call failed: ' . $response->body());
                    return null;
                }

                $data = $response->json();
                $current = $data['current'] ?? [];
                $currentWeather = $current['weather'][0] ?? [];

                return [
                    'temperature' => round($current['temp'] ?? 0),
                    'feels_like' => round($current['feels_like'] ?? 0),
                    'condition' => $currentWeather['main'] ?? 'Weather',
                    'description' => $currentWeather['description'] ?? 'current conditions',
                    'icon' => $currentWeather['icon'] ?? '01d',
                    'humidity' => $current['humidity'] ?? null,
                    'wind_speed' => isset($current['wind_speed']) ? round($current['wind_speed'] * 3.6, 1) : null,
                    'city' => $label ?: 'Destination',
                    'country' => '',
                    'source' => 'onecall',
                    'alerts' => collect($data['alerts'] ?? [])->map(fn ($alert) => [
                        'event' => $alert['event'] ?? 'Weather alert',
                        'description' => $alert['description'] ?? null,
                    ])->take(2)->values()->all(),
                    'forecast_days' => collect($data['daily'] ?? [])->take(8)->map(function ($day) {
                        $weather = $day['weather'][0] ?? [];

                        return [
                            'date' => isset($day['dt']) ? date('M j', $day['dt']) : '',
                            'day' => isset($day['dt']) ? date('D', $day['dt']) : '',
                            'min' => round($day['temp']['min'] ?? 0),
                            'max' => round($day['temp']['max'] ?? 0),
                            'condition' => $weather['main'] ?? 'Weather',
                            'description' => $weather['description'] ?? '',
                            'icon' => $weather['icon'] ?? '01d',
                        ];
                    })->values()->all(),
                ];
            } catch (\Exception $e) {
                \Log::warning('OpenWeather One Call exception: ' . $e->getMessage());
                return null;
            }
        });
    }
}
