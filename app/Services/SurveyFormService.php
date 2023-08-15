<?php

namespace App\Services;

use App\Api\Exceptions\FatalErrorException;
use App\Api\Service\CommonService;
use App\Events\SurveyFormCreated;
use App\Http\Resources\SurveyFormResource;
use App\Http\Resources\UserSurveyResource;
use App\Models\SurveyForm;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Throwable;

class SurveyFormService extends CommonService
{

    /**
     * @return JsonResponse
     * @throws FatalErrorException
     */
    public function get(): JsonResponse
    {
        try {
            $data = User::auth()->load('surveyForm');

            return response()->json(new UserSurveyResource($data), 200);
//            return $this->formatter()->make(
//                new UserSurveyResource($data),
//                200
//            );
        } catch (Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return JsonResponse
     * @throws FatalErrorException
     */
    public function store($data): JsonResponse
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

            SurveyFormCreated::dispatch($survey);

            return $this->formatter()->make(
                new SurveyFormResource($survey),
                201
            );
        } catch (Throwable $e) {
            logger()->debug($e->getMessage() . $e->getLine() . ' ----- ' . $e->getFile());
            throw new FatalErrorException($e->getMessage());
        }
    }

}
