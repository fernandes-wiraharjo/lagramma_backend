<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

function getMokaToken()
{
    return DB::table('moka_tokens')->latest('created_at')->value('access_token');
}

function insertApiErrorLog($name, $url, $method, $headers, $queryParams, $requestBody, $statusCode, $responseBody)
{
    DB::table('log_api_errors')->insert([
        'name' => $name,
        'url' => $url,
        'method' => $method,
        'header' => $headers,
        'request_param' => $queryParams,
        'request_body' => $requestBody,
        'status_code' => $statusCode,
        'response' => $responseBody,
        'created_by' => null,
        'updated_by' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function refreshMokaToken()
{
    $baseUrl = env('MOKA_API_URL');

    // Get the latest refresh token from the database
    $latestToken = DB::table('moka_tokens')->latest('updated_at')->first();

    $credentials = [
        'client_id' => env('MOKA_CLIENT_ID'),
        'client_secret' => env('MOKA_CLIENT_SECRET'),
        'grant_type' => 'refresh_token',
        'refresh_token' => $latestToken->refresh_token
    ];

    $response = Http::post($baseUrl . '/oauth/token', $credentials);

    if ($response->successful()) {
        $responseData = $response->json();

        $newAccessToken = $responseData['access_token'];
        $newRefreshToken = $responseData['refresh_token'];
        $expiresIn = $responseData['expires_in']; // Expiry time in seconds

         // Calculate the new expiration time
         $expiresAt = now()->addSeconds($expiresIn);

        // Update in database
        DB::table('moka_tokens')->updateOrInsert(
            ['id' => 1], // Assuming only one row exists
            [
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
            ]
        );

        return $newAccessToken;
    } else {
        $status = $response->status();
        Log::error("Refresh MOKA API Token Error: HTTP {$status}");
        insertApiErrorLog('Refresh MOKA API Token', "$baseUrl/oauth/token", 'POST', null, null, null, $response->status(), $response->body());
        return null;
    }
}
