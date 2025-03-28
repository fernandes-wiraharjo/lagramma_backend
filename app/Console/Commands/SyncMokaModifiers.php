<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Modifier;
use App\Models\ModifierOption;

class SyncMokaModifiers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-moka-modifiers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master product modifier from MOKA';

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

        $url = "{$baseUrl}/v1/outlets/{$outletId}/modifiers?per_page=50";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $responseData = $response->json();
            $listData = $responseData['data']['modifier'] ?? [];

            foreach ($listData as $data) {
                //save modifiers
                $modifier = Modifier::updateOrCreate(
                    ['moka_id_modifier' => $data['id']], // Unique identifier for update
                    [
                        'name'        => $data['name'],
                        'updated_at'  => now()
                    ]
                );

                //save modifier options
                if (!empty($data['active_options'])) {
                    foreach ($data['active_options'] as $option) {
                        ModifierOption::updateOrCreate(
                            ['moka_id_modifier_option' => $option['id']], // Unique identifier for update
                            [
                                'id_modifier' => $modifier->id, // Use the ID of the saved Modifier
                                'name'        => $option['name'],
                                'price'       => $option['price'],
                                'position'    => $option['position'],
                                'updated_at'  => now()
                            ]
                        );
                    }
                }
            }

            Log::info('Sync MOKA Modifiers successfully.');
        } else {
            $status = $response->status();

            // If token expired, refresh it and retry
            if ($status === 401) {
                $newToken = refreshMokaToken();
                if ($newToken) {
                    Log::info('Token refreshed. Retrying Sync MOKA Modifiers...');
                    $this->handle(); // Retry fetching data
                }
            } else {
                Log::error("Sync MOKA Modifiers API Error: HTTP {$status}");
                insertApiErrorLog('Sync MOKA Modifiers', "{$baseUrl}/v1/outlets/outlet_id/modifiers", 'GET', null, null, null, $status, $response->body());
            }
        }
    }
}
