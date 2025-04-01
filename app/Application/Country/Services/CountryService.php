<?php

namespace App\Application\Country\Services;

class CountryService
{
    /**
     * @throws \JsonException
     */
    public function getAllCountries(): array
    {
        $countries = storage_path("app/data/countries.json");
        return json_decode(file_get_contents($countries), true, 512, JSON_THROW_ON_ERROR);
    }
}
