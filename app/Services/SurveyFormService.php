<?php

namespace App\Services;

use App\Api\Service\CommonService;
use App\Http\Resources\UserSurveyResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SurveyFormService extends CommonService
{

    public function get()
    {
        $data = Auth::user()->load('surveyForm');

        return $this->formatter()->make(
            new UserSurveyResource($data)
        );
    }
}
