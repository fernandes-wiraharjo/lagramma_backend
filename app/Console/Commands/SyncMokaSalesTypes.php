<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SalesType;

class SyncMokaSalesTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-moka-sales-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master sales type from MOKA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = env('MOKA_API_URL');
        $outletId = env('MOKA_OUTLET_ID');
        $token = getMokaToken();

        if (!$token) {
            Log::error('Failed to retrieve MOKA API token.');
            return;
        }

        $url = "{$baseUrl}/v1/outlets/{$outletId}/sales_type?per_page=50";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $responseData = $response->json();
            $listData = $responseData['data']['results'] ?? [];

            foreach ($listData as $data) {
                SalesType::updateOrCreate(
                    ['moka_id_sales_type' => $data['id']], // Unique identifier for update
                    [
                        'name'        => $data['name'],
                        'updated_at'  => now()
                    ]
                );
            }

            Log::info('Sync MOKA Sales Type successfully.');
        } else {
            $status = $response->status();

            // If token expired, refresh it and retry
            if ($status === 401) {
                $newToken = refreshMokaToken();
                if ($newToken) {
                    Log::info('Token refreshed. Retrying Sync MOKA Sales Type...');
                    $this->handle(); // Retry fetching data
                }
            } else {
                Log::error("Sync MOKA Sales Type API Error: HTTP {$status}");
                insertApiErrorLog('Sync MOKA Sales Type', "{$baseUrl}/v1/outlets/outlet_id/sales_type", 'GET', null, null, null, $status, $response->body());
            }
        }
    }
}
