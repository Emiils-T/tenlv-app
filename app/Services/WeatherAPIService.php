<?php
namespace App\Services;

use App\Models\Tournament;

class WeatherAPIService{


    public function getWeather(Tournament $tournament): array
    {

        $key = env('API_KEY');
        $address = $tournament->court->address;
        $addressArray = explode(",",$address);
        $city = trim($addressArray[1]);
        $countryCode = "LV";

        //iegūstam lat un lon no city name
        $geo = "http://api.openweathermap.org/geo/1.0/direct?q={$city},{$countryCode}&limit=2&appid={$key}";
        $response = file_get_contents($geo);
        $location = json_decode($response,true);

        $lat=$location[0]['lat'];
        $lon=$location[0]['lon'];


        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&appid={$key}&units=metric";
        $weatherResponse = file_get_contents($weatherUrl);
        $weather = json_decode($weatherResponse,true);

        $weatherForecast = [
            'icon'=>$weather['weather'][0]['icon'],
            'temp' => $weather['main']['temp'],
            'wind'=>[
                'speed' => $weather['wind']['speed'],
                'gust'=>$weather['wind']['gust']
            ]
        ];


        return $weatherForecast;
    }
}
