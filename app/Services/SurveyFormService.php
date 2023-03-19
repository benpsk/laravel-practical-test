<?php

namespace App\Services;

use App\Api\Exceptions\FatalErrorException;
use App\Api\Service\CommonService;
use App\Http\Resources\SurveyFormResource;
use App\Http\Resources\UserSurveyResource;
use App\Models\SurveyForm;
use App\Models\User;
class SurveyFormService extends CommonService
{

    public function get()
    {
        try {
            $data = User::auth()->load('surveyForm');

            return $this->formatter()->make(
                new UserSurveyResource($data)
            );
        } catch (\Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

    public function store($data)
    {
        try {
            $user = User::auth();
            $survey = new SurveyForm([
                'name' => $data['name'],
                'phone_no' => $data['phone_no'],
                'gender' => $data['gender'] ?? null,
                'dob' => $data['dob'] ?? null
            ]);

            $user->surveyForm()->save($survey);

            return $this->formatter()->make(
                new SurveyFormResource($survey)
            );
        } catch (\Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

}
