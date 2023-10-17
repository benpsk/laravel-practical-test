<?php

namespace App\Http\Middleware;

use App\Api\Service\Formatter;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class HandleApiResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $api = Formatter::factory()->setStart($start);
        $response = $next($request);
        if ($this->isApi($request, $response)) {
            $statusCode = $response->getStatusCode();
            $data = $response->getData();
            return $api->make($data, $statusCode);
        }
        return $response;
    }
    protected function isApi($request, $response): bool
    {
        return $response instanceof JsonResponse &&
            $request->expectsJson() &&
            $response->isSuccessful();
    }
}
