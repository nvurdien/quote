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
            'smoker' => 'required|boolean',
            'gender' => 'required|in:M,F',
            'term' => 'required|in:10,15,20,30',
            'coverage_amount' => 'required|integer|min:100000|max:1000000'
        ]);

        $response = Http::get('https://plumlife-api-lab.azurewebsites.net/api/quote', [
            'productId' => 1,
            'riskClass' => 1,
            'dob' => $validated['dob'],
            'state' => $validated['state'],
            'smoker' => $validated['smoker'],
            'gender' => $validated['gender'],
            'term' => $validated['term'],
            'coverage_amount' => $validated['coverage_amount']
        ]);

        if ($response->failed()) {
            Log::error('Quote API failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $response->url()
            ]);
            return response()->json([
                'error' => 'Quote API failed', 'details' => $response->body()
            ], $response->status());
        }

        $responseData = $response->json();
        $term = (int) $validated['term'];
        $requestedCoverage = (int) $validated['coverage_amount'];
        
        // Get available amounts and sort
        $availableCoverages = collect($responseData['coverage_amounts'])->sort()->values();

        // Find closest lower or equal amount
        $matchedCoverage = $availableCoverages->filter(function ($amount) use ($requestedCoverage) {
            return $amount <= $requestedCoverage;
        })->last();

        if (!$matchedCoverage || !isset($responseData['quotes'][$term])) {
            return response()->json(['error' => 'No matching quote found'], 404);
        }

        // Get the quotes for that term
        $quotesForTerm = $responseData['quotes'][$term];

        // Find the quote with the matched coverage amount
        $quote = collect($quotesForTerm)->firstWhere('faceAmount', $matchedCoverage);
        if ($quote) {
            return response()->json([
                'matched_coverage_amount' => $matchedCoverage,
                'monthly_premium' => $quote['monthlyPremium'],
                'quarterly_premium' => $quote['quarterlyPremium'],
                'semi_annual_premium' => $quote['semiannuallyPremium'],
                'annual_premium' => $quote['annuallyPremium']
            ], 200, [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        }
        return response()->json(['error' => 'No quote found for the requested coverage amount'], 404);
    }
}