<?php

namespace Tests\Unit\Services;

use App\Services\WeatherService;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherServiceTest extends TestCase
{
    private WeatherService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new WeatherService();
        Cache::flush();
    }

    /**
     * Test fetching weather by location name
     */
    public function test_get_weather_by_location_returns_weather_data()
    {
        Http::fake([
            '*' => Http::response([
                'main' => ['temp' => 25.5, 'feels_like' => 26.0, 'humidity' => 65],
                'weather' => [['main' => 'Sunny', 'description' => 'Clear sky', 'icon' => '01d']],
                'wind' => ['speed' => 5.5],
                'name' => 'Manila',
                'sys' => ['country' => 'PH'],
            ]),
        ]);

        $weather = $this->service->getWeather('Manila');

        $this->assertNotNull($weather);
        $this->assertArrayHasKey('temperature', $weather);
        $this->assertArrayHasKey('condition', $weather);
        $this->assertArrayHasKey('city', $weather);
        $this->assertEquals('Manila', $weather['city']);
        $this->assertEquals('Sunny', $weather['condition']);
    }

    /**
     * Test fetching weather by coordinates
     */
    public function test_get_weather_by_coordinates_returns_weather_data()
    {
        Http::fake([
            '*' => Http::response([
                'main' => ['temp' => 28.0, 'feels_like' => 29.5, 'humidity' => 70],
                'weather' => [['main' => 'Rainy', 'description' => 'Light rain', 'icon' => '10d']],
                'wind' => ['speed' => 3.2],
                'name' => 'Boracay',
                'sys' => ['country' => 'PH'],
            ]),
        ]);

        $weather = $this->service->getWeatherByCoordinates(11.9673, 121.9272, 'Boracay');

        $this->assertNotNull($weather);
        $this->assertEquals(28, $weather['temperature']);
        $this->assertEquals('Rainy', $weather['condition']);
        $this->assertEquals('Boracay', $weather['city']);
    }

    /**
     * Test weather caching works correctly
     */
    public function test_weather_data_is_cached()
    {
        Http::fake([
            '*' => Http::response([
                'main' => ['temp' => 25.0, 'feels_like' => 25.0, 'humidity' => 60],
                'weather' => [['main' => 'Cloudy', 'description' => 'Overcast clouds', 'icon' => '04d']],
                'wind' => ['speed' => 4.0],
                'name' => 'Palawan',
                'sys' => ['country' => 'PH'],
            ]),
        ]);

        $first = $this->service->getWeather('Palawan');
        $second = $this->service->getWeather('Palawan');

        Http::assertSentCount(1); // Should only be called once due to caching
        $this->assertEquals($first, $second);
    }

    /**
     * Test handling API failure gracefully
     */
    public function test_get_weather_returns_null_on_api_failure()
    {
        Http::fake(['*' => Http::response([], 401)]);

        $weather = $this->service->getWeather('InvalidLocation');

        $this->assertNull($weather);
    }

    /**
     * Test handling connection timeout
     */
    public function test_get_weather_handles_connection_timeout()
    {
        Http::fake(['*' => Http::response([], 500)]);

        $weather = $this->service->getWeather('TestLocation');

        $this->assertNull($weather);
    }

    /**
     * Test wind speed conversion from m/s to km/h
     */
    public function test_wind_speed_conversion()
    {
        Http::fake([
            '*' => Http::response([
                'main' => ['temp' => 20.0, 'feels_like' => 20.0, 'humidity' => 50],
                'weather' => [['main' => 'Clear', 'description' => 'Clear sky', 'icon' => '01d']],
                'wind' => ['speed' => 10.0], // 10 m/s
                'name' => 'TestCity',
                'sys' => ['country' => 'PH'],
            ]),
        ]);

        $weather = $this->service->getWeather('TestCity');

        // 10 m/s * 3.6 = 36 km/h
        $this->assertEquals(36.0, $weather['wind_speed']);
    }
}
