<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NumberController extends Controller
{
    public function classify(Request $request)
    {
        $number = $request->query('number');

        // Validate input: Ensure it's a valid integer
        if (!is_numeric($number) || strpos($number, '.') !== false) {
            return response()->json(['number' => "alphabet", 'error' => true], 400);
        }

        $originalNumber = (int) $number; // Keep original number
        $number = abs($originalNumber); // Convert negative to positive

        $isPrime = $this->isPrime($number);
        $isPerfect = $this->isPerfect($number);
        $isArmstrong = $this->isArmstrong($number);
        $digitSum = $this->digitSum($number);
        $parity = $number % 2 === 0 ? 'even' : 'odd';

        // Determine properties
        $properties = $isArmstrong ? ['armstrong', $parity] : [$parity];

        // Fetch fun fact from Numbers API
        $funFact = $this->fetchFunFact($number);

        // Return JSON response in required format
        return response()->json([
            'number' => $originalNumber, // Show original input
            'is_prime' => $isPrime,
            'is_perfect' => $isPerfect,
            'properties' => $properties,
            'digit_sum' => $digitSum,
            'fun_fact' => $funFact,
        ]);
    }

    private function isPrime($num)
    {
        if ($num < 2) return false;
        for ($i = 2; $i * $i <= $num; $i++) {
            if ($num % $i == 0) return false;
        }
        return true;
    }

    private function isPerfect($num)
    {
        if ($num < 2) return false;
        $sum = 1;
        for ($i = 2; $i * $i <= $num; $i++) {
            if ($num % $i == 0) {
                $sum += $i;
                if ($i !== $num / $i) $sum += $num / $i;
            }
        }
        return $sum === $num;
    }

    private function isArmstrong($num)
    {
        $sum = 0;
        $digits = str_split($num);
        $power = count($digits);
        foreach ($digits as $digit) {
            $sum += pow($digit, $power);
        }
        return $sum === $num;
    }

    private function digitSum($num)
    {
        return array_sum(str_split($num));
    }

    private function fetchFunFact($num)
    {
        $response = Http::get("http://numbersapi.com/{$num}/math");
        $funFact = $response->successful() ? $response->body() : "No fact available.";

        return $funFact;
    }
}
