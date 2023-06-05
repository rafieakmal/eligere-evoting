<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use App\Spiders\MySpider;
use RoachPHP\Http\Response;
use RoachPHP\Roach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        $start  = Roach::startSpider(MySpider::class);
        $data  = Roach::collectSpider(MySpider::class);
        $post_data = DB::table('weather')
            ->orderBy('weather.created_at', 'DESC')
            ->limit(1)
            ->get();

        return response()->json($post_data, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function gempa(Request $request)
    {
        $response = Http::get('https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json');
        return $response->json();
    }

    public function terkini(Request $request)
    {
        $response = Http::get('https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json');
        return $response->json();
    }

    public function dirasakan(Request $request)
    {
        $response = Http::get('https://data.bmkg.go.id/DataMKG/TEWS/gempadirasakan.json');
        return $response->json();
    }

}
