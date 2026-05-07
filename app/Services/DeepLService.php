<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepLService
{
    public function translateBatch(array $texts, bool $isHtml = false): array
    {
        $apiKey = config('services.deepl.api_key');

        if (blank($apiKey) || empty(array_filter($texts))) {
            return $texts;
        }

        $payload = [
            'text' => $texts,
            'target_lang' => 'EN',
            'source_lang' => 'ID',
        ];

        if ($isHtml) {
            $payload['tag_handling'] = 'html';
        }

        $client = Http::withHeaders(['Authorization' => 'DeepL-Auth-Key '.$apiKey]);

        // Disable SSL verification in local dev (Laragon lacks a CA bundle)
        if (app()->isLocal()) {
            $client = $client->withoutVerifying();
        }

        $response = $client->post('https://api-free.deepl.com/v2/translate', $payload);

        if (! $response->successful()) {
            return $texts;
        }

        return array_column($response->json('translations', []), 'text');
    }
}
