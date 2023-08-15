<?php

namespace App\Http\Middleware;

use App\Api\Service\CommonService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleSuccessResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse && $response->isSuccessful() && $request->expectsJson()) {
            $data = $response->getData();
            $statusCode = $response->getStatusCode();
            $common = new CommonService();
            return $common->formatter()->make($data, $statusCode);
        }

        return $response;
    }
}
