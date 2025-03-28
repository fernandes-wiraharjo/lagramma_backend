<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class SyncMokaCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-moka-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master product category from MOKA';

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

        $url = "{$baseUrl}/v1/outlets/{$outletId}/categories?per_page=50";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $responseData = $response->json();
            $categories = $responseData['data']['category'] ?? [];

            foreach ($categories as $category) {
                Category::updateOrCreate(
                    ['moka_id_category' => $category['id']], // Unique identifier for update
                    [
                        'name'        => $category['name'],
                        'description' => $category['description'],
                        'updated_at'  => now()
                    ]
                );
            }

            Log::info('Sync MOKA Product Categories successfully.');
        } else {
            $status = $response->status();

            // If token expired, refresh it and retry
            if ($status === 401) {
                $newToken = refreshMokaToken();
                if ($newToken) {
                    Log::info('Token refreshed. Retrying Sync MOKA Product Categories...');
                    $this->handle(); // Retry fetching categories
                }
            } else {
                Log::error("Sync MOKA Product Categories API Error: HTTP {$status}");
                insertApiErrorLog('Sync MOKA Product Categories', "{$baseUrl}/v1/outlets/outlet_id/categories", 'GET', null, null, null, $status, $response->body());
            }
        }
    }
}
