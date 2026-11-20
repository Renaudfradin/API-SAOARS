<?php

namespace App\Services\Fanadata;

use Generator;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class FanadataClient
{
    public function __construct(private string $base = 'https://api.fanadata.com/saoub') {}

    /**
     * @return array<string, mixed>
     */
    public function get(string $path): array
    {
        $url = str_starts_with($path, 'http') ? $path : $this->base.'/'.ltrim($path, '/');

        if (! str_contains($url, '?') && ! str_ends_with($url, '/')) {
            $url .= '/';
        }

        $payload = $this->request()->get($url)->throw()->json();

        return is_array($payload) ? $payload : [];
    }

    /**
     * @return Generator<int, array<string, mixed>>
     */
    public function paginate(string $path): Generator
    {
        $url = $path;

        while ($url) {
            $page = $this->get($url);

            foreach ($page['results'] ?? [] as $row) {
                if (is_array($row)) {
                    yield $row;
                }
            }

            $next = $page['next'] ?? null;
            $url = is_string($next) && $next !== '' ? $next : null;
        }
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Referer' => 'https://saoub.fanadata.com/',
            'User-Agent' => 'API-SAOARS fanadata sync',
        ])->timeout(20)->retry(2, 200);
    }
}
