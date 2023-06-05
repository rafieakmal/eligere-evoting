<?php

namespace App\Spiders;

use Generator;
use App\Spiders\Processors\SaveWeatherToDatabaseProcessor;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;

class MySpider extends BasicSpider
{
    public array $startUrls = [
        'https://weather.com/id-ID/cuaca/sekarang/l/IDXX0022:1:ID'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        SaveWeatherToDatabaseProcessor::class,
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    public function parse(Response $response): Generator
    {
        $country = $response->filter('.CurrentConditions--header--kbXKR')->text('');
        $currentConditions = $response->filter('.CurrentConditions--tempValue--MHmYY')->text('');
        $pharseValue = $response->filter('.CurrentConditions--phraseValue--mZC_p')->text('');
        $cardFirstHeading = $response->filterXPath('//*[@id="WxuTodayWeatherCard-main-486ce56c-74e0-4152-bd76-7aea8e98520a"]/section/header/h2')->text('');
        $morningTemprature = $response->filterXPath('//*[@id="WxuTodayWeatherCard-main-486ce56c-74e0-4152-bd76-7aea8e98520a"]/section/div/ul/li[1]/a/div[1]/span')->text('');
        $afternoonFirstTemprature = $response->filterXPath('//*[@id="WxuTodayWeatherCard-main-486ce56c-74e0-4152-bd76-7aea8e98520a"]/section/div/ul/li[2]/a/div[1]/span')->text('');
        $afternoonSecondTemprature = $response->filterXPath('//*[@id="WxuTodayWeatherCard-main-486ce56c-74e0-4152-bd76-7aea8e98520a"]/section/div/ul/li[3]/a/div[1]/span')->text('');
        $eveningTemprature = $response->filterXPath('//*[@id="WxuTodayWeatherCard-main-486ce56c-74e0-4152-bd76-7aea8e98520a"]/section/div/ul/li[4]/a/div[1]/span')->text('');
        $aqi = $response->filterXPath('//*[@id="WxuAirQuality-sidebar-aa4a4fb6-4a9b-43be-9004-b14790f57d73"]/div/section/div/div/div[1]/svg/text')->text('');
        $activity = $response->filter('.HealthActivitiesListItem--heading--3_RUg')->text('');
        $cardSecondHeading = $response->filterXPath('//*[@id="todayDetails"]/section/header/h2')->text('');
        $todayConditions = $response->filter('.TodayDetailsCard--feelsLikeTempValue--2icPt')->text('');
        $maxTemprature = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[1]/div[2]/span[1]')->text('');
        $minTemprature = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[1]/div[2]/span[2]')->text('');
        $moisture = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[3]/div[2]/span')->text('');
        $pressure = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[5]/div[2]/span/text()')->text('');
        $visiblity = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[7]/div[2]/span')->text('');
        $wind = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[2]/div[2]/span/text()')->text('');
        $dew_point = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[4]/div[2]/span')->text('');
        $uvIndex = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[6]/div[2]/span')->text('');
        $moon = $response->filterXPath('//*[@id="todayDetails"]/section/div[2]/div[8]/div[2]')->text('');
        $cardThirdHeading = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/header/h2')->text('');
        $timeFirstTitle = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[1]/a/h3/span')->text('');
        $firstTemprature = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[1]/a/div[1]/span')->text('');
        $timeSecondTitle = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[2]/a/h3/span')->text('');
        $secondTemprature = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[2]/a/div[1]/span')->text('');
        $timeThirdTitle = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[3]/a/h3/span')->text('');
        $thirdTemprature = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[3]/a/div[1]/span')->text('');
        $timeFourthTitle = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[4]/a/h3/span')->text('');
        $fourthTemprature = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[4]/a/div[1]/span')->text('');
        $timeLastTitle = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[5]/a/h3/span')->text('');
        $lastTemprature = $response->filterXPath('//*[@id="WxuHourlyWeatherCard-main-29584a07-3742-4598-bc2a-f950a9a4d900"]/section/div/ul/li[5]/a/div[1]/span')->text('');

        yield $this->item([
            'country' => $country,
            'current_condition' => $currentConditions,
            'pharse_value' => $pharseValue,
            'card_first_heading' => $cardFirstHeading,
            'suhu_pagi' => $morningTemprature,
            'suhu_siang' => $afternoonFirstTemprature,
            'suhu_sore' => $afternoonSecondTemprature,
            'suhu_malam' => $eveningTemprature,
            'aqi' => $aqi,
            'activity' => $activity,
            'card_second_heading' => $cardSecondHeading,
            'suhu_hari_ini' => $todayConditions,
            'suhu_max' => $maxTemprature,
            'suhu_min' => $minTemprature,
            'kelembapan' => $moisture,
            'tekanan' => $pressure,
            'jarak_pandang' => $visiblity,
            'angin' => $wind,
            'embun' => $dew_point,
            'indeks_uv' => $uvIndex,
            'fase_bulan' => $moon,
            'card_third_heading' => $cardThirdHeading,
            'jam_sekarang' => $timeFirstTitle,
            'suhu_jam_sekarang' => $firstTemprature,
            'jam_kedua' => $timeSecondTitle,
            'suhu_jam_kedua' => $secondTemprature,
            'jam_ketiga' => $timeThirdTitle,
            'suhu_jam_ketiga' => $thirdTemprature,
            'jam_keempat' => $timeFourthTitle,
            'suhu_jam_keempat' => $fourthTemprature,
            'jam_terakhir' => $timeLastTitle,
            'suhu_jam_terakhir' => $lastTemprature,
        ]); 
    }
}