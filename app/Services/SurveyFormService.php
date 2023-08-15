<?php

namespace App\Services;

use App\Api\Exceptions\FatalErrorException;
use App\Events\SurveyFormCreated;
use App\Models\SurveyForm;
use App\Models\User;
use Throwable;


class SurveyFormService
{

    /**
     * @throws FatalErrorException
     */
    public function get(): User
    {
        try {
            return User::auth()->load('surveyForm');
        } catch (Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

    /**
     * @throws FatalErrorException
     */
    public function store($data): SurveyForm
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

//            SurveyFormCreated::dispatch($survey);
            return $survey;
        } catch (Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

}
