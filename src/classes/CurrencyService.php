<?php

declare(strict_types=1);

namespace App;

class CurrencyService
{
    public function getRate(string $targetCurrency): float
    {
        $ch = curl_init('https://open.er-api.com/v6/latest/USD');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception('Ошибка при получении курса валют. HTTP код: ' . $httpCode);
        }

        $data = json_decode($response, true);
        if (!isset($data['rates'][$targetCurrency])) {
            throw new \Exception('Курс валюты ' . $targetCurrency . ' не найден.');
        }

        return $data['rates'][$targetCurrency];
    }
}