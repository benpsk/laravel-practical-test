<?php

namespace App\Services;

use App\Api\Exceptions\FatalErrorException;
use App\Models\SurveyForm;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Throwable;


class SurveyFormService
{

    /**
     * @throws FatalErrorException
     */
    public function get(): Builder|Model
    {
        try {
            return User::query()->where('id', auth()->user()->id)
                ->with(['surveyForm' => function ($q) {
                    $q->limit(10000);
                }])->first();
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

            /** @var User $user */
            $user->surveyForm()->save($survey);

//            SurveyFormCreated::dispatch($survey);
            return $survey;
        } catch (Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

}
