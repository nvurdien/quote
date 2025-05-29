<?php

// app/Http/Controllers/QuoteController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    public function generateQuote(Request $request)
    {
        Log::info('Quote generation request received', [
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'data' => $request->all()
        ]);
        $validated = $request->validate([
            'dob' => 'required|date',
            'state' => 'required|string|size:2',
            'is_smoker' => 'required|boolean',
            'gender' => 'required|in:male,female',
            'term' => 'required|in:10,15,20,30',
            'coverage_amount' => 'required|integer|min:100000|max:1000000'
        ]);

        $response = Http::post('https://plumlife-api-stage.azurefd.net/api/quote', [
            'productId' => 1,
            'dob' => $validated['dob'],
            'state' => $validated['state'],
            'smoker' => $validated['is_smoker'],
            'gender' => $validated['gender'],
            'term' => $validated['term'],
            'coverage_amount' => $validated['coverage_amount']
        ]);

        if ($response->failed()) {

            return response()->json(['error' => 'Quote API failed', 'details' => $response->body()], 500);
        }

        $quotes = $response->json();
        $requestedCoverage = (int)$validated['coverage_amount'];
        
        foreach ($quotes as $quote) {
            if ($quote['coverage_amount'] === $requestedCoverage) {
                return response()->json([
                    'monthly_premium' => $quote['monthly_premium'],
                    'quarterly_premium' => $quote['quarterly_premium'],
                    'semi_annual_premium' => $quote['semi_annual_premium'],
                    'annual_premium' => $quote['annual_premium']
                ]);
            }
        }

        return response()->json(['error' => 'Quote not found'], 404);
    }
}