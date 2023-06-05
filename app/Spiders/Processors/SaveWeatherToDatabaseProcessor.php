<?php

namespace App\Spiders\Processors;

use App\Models\Weather;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class SaveWeatherToDatabaseProcessor implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        Weather::create([
            'country' => $item->get('country'),
            'current_conditions' => $item->get('current_condition'),
            'pharse_value' => $item->get('pharse_value'),
            'card_first_heading' => $item->get('card_first_heading'),
            'suhu_pagi' => $item->get('suhu_pagi'),
            'suhu_siang' => $item->get('suhu_siang'),
            'suhu_sore' => $item->get('suhu_sore'),
            'suhu_malam' => $item->get('suhu_malam'),
            'aqi' => $item->get('aqi'),
            'activity' => $item->get('activity'),
            'card_second_heading' => $item->get('card_second_heading'),
            'suhu_hari_ini' => $item->get('suhu_hari_ini'),
            'suhu_max' => $item->get('suhu_max'),
            'suhu_min' => $item->get('suhu_min'),
            'kelembapan' => $item->get('kelembapan'),
            'tekanan' => $item->get('tekanan'),
            'jarak_pandang' => $item->get('jarak_pandang'),
            'angin' => $item->get('angin'),
            'embun' => $item->get('embun'),
            'indeks_uv' => $item->get('indeks_uv'),
            'fase_bulan' => $item->get('fase_bulan'),
            'card_third_heading' => $item->get('card_third_heading'),
            'jam_sekarang' => $item->get('jam_sekarang'),
            'suhu_jam_sekarang' => $item->get('suhu_jam_sekarang'),
            'jam_kedua' => $item->get('jam_kedua'),
            'suhu_jam_kedua' => $item->get('suhu_jam_kedua'),
            'jam_ketiga' => $item->get('jam_ketiga'),
            'suhu_jam_ketiga' => $item->get('suhu_jam_ketiga'),
            'jam_keempat' => $item->get('jam_keempat'),
            'suhu_jam_keempat' => $item->get('suhu_jam_keempat'),
            'jam_terakhir' => $item->get('jam_terakhir'),
            'suhu_jam_terakhir' => $item->get('suhu_jam_terakhir'),
        ]);

        return $item;
    }

    private function defaultOptions(): array
    {
        return [
            'threshold' => 4
        ];
    }
}