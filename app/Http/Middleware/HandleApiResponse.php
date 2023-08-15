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
        $response = $next($request);

        if ($response instanceof JsonResponse && $request->expectsJson() && $response->isSuccessful()) {

            $statusCode = $response->getStatusCode();

            $data = $response->getData();
            return Formatter::factory()->make($data, $statusCode);

        }

        return $response;
    }
}
