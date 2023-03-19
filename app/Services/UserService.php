<?php

namespace App\Services;

use App\Api\Service\CommonService;
use App\Http\Resources\UserResource;

class UserService extends CommonService
{
    /**
     * @param $data
     */
    public function get($user)
    {
        return $this->formatter()->make(new UserResource($user));
    }
}
