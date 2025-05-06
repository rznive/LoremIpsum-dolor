<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class plnController extends Controller
{
    public function cekPln(Request $request)
    {
        $customerNumber = $request->input('customer_number');

        if (!$customerNumber) {
            return response()->json(['error' => 'customer_number is required'], 400);
        }
        $tokenResponse = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Content-Type' => 'application/json',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-ch-ua' => '"Google Chrome";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
            'sec-ch-ua-mobile' => '?0',
            'origin' => 'https://www.bukalapak.com',
            'sec-fetch-site' => 'same-origin',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-dest' => 'empty',
            'referer' => 'https://www.bukalapak.com/listrik-pln/tagihan-listrik',
            'accept-language' => 'en-US,en;q=0.9',
            'priority' => 'u=1, i',
        ])->post('https://www.bukalapak.com/westeros_auth_proxies', [
            'application_id' => 1,
            'authenticity_token' => ''
        ]);

        if (!$tokenResponse->ok()) {
            return response()->json(['error' => 'Gagal mengambil access token'], 500);
        }

        $accessToken = $tokenResponse->json('access_token');

        if (!$accessToken) {
            return response()->json(['error' => 'Access token tidak ditemukan'], 500);
        }

        $inquiryResponse = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Content-Type' => 'application/json',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-ch-ua' => '"Google Chrome";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
            'sec-ch-ua-mobile' => '?0',
            'origin' => 'https://www.bukalapak.com',
            'sec-fetch-site' => 'same-site',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-dest' => 'empty',
            'referer' => 'https://www.bukalapak.com/listrik-pln/tagihan-listrik',
            'accept-language' => 'en-US,en;q=0.9',
            'priority' => 'u=1, i',
        ])->post("https://api.bukalapak.com/electricities/postpaid-inquiries?access_token={$accessToken}", [
            'customer_number' => $customerNumber
        ]);

        if (!$inquiryResponse->ok()) {
            return response()->json(['error' => 'Gagal melakukan inquiry Tagihan PLN'], 500);
        }

        return response()->json($inquiryResponse->json());
    }
}
