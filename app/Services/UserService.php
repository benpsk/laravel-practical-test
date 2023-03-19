<?php

namespace App\Services;

use App\Api\Service\CommonService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserService extends CommonService
{

    /**
     * @param $user
     * @return JsonResponse
     */
    public function get($user): JsonResponse
    {
        return $this->formatter()
            ->make(new UserResource($user), 200);
    }
}
