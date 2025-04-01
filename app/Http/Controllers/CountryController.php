<?php

namespace App\Http\Controllers;

use App\Application\Country\Services\CountryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected CountryService $countryService;
    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    /**
     */
    public function getCountries(): JsonResponse
    {
        try {
            $countries = $this->countryService->getAllCountries();
            return response()->json([
                'success' => true,
                'countries' => $countries
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
