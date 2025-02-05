<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NumberController extends Controller
{
    public function classify(Request $request)
    {
        // Validate input
        $number = $request->query('number');

        if (!is_numeric($number) || strpos($number, '.') !== false) {
            return response()->json(['number' => "alphabet", 'error' => true], 400);
        }

        $number = abs((int) $number); // Convert negative to positive

        $isPrime = $this->isPrime($number);
        $isPerfect = $this->isPerfect($number);
        $isArmstrong = $this->isArmstrong($number);
        $digitSum = $this->digitSum($number);
        $parity = $number % 2 === 0 ? 'even' : 'odd';

        // Determine properties
        $properties = [];
        if ($isArmstrong) $properties[] = "armstrong";
        $properties[] = $parity;

        // Fetch fun fact
        $funFact = $this->fetchFunFact($number);

        // Return structured JSON response
        return response()->json([
            'number' => $number,
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

    // If the number is Armstrong, override the fun fact
    if ($this->isArmstrong($num)) {
        $funFact = "{$num} is an Armstrong number because " . implode(" + ", array_map(
            fn($digit) => "{$digit}^" . strlen($num),
            str_split($num)
        )) . " = {$num}";
    }

    return $funFact;
}

}
