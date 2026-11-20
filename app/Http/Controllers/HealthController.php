<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class HealthController extends Controller
{
    #[OA\Get(
        path: '/api/health',
        summary: 'API and database health',
        tags: ['Home'],
        responses: [
            new OA\Response(response: 200, description: 'The API and the database are reachable'),
            new OA\Response(response: 503, description: 'The database is disconnected'),
        ],
    )]
    public function __invoke(): JsonResponse
    {
        $database = rescue(
            fn () => DB::connection()->getPdo() ? 'connected' : 'disconnected',
            'disconnected',
            false,
        );

        $healthy = $database === 'connected';

        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'database' => $database,
        ], $healthy ? 200 : 503);
    }
}
