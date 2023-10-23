<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Exceptions\FatalErrorException;
use App\Api\Exceptions\UnauthorizedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Resources\SurveyFormResource;
use App\Http\Resources\UserSurveyResource;
use App\Models\SurveyForm;
use App\Services\SurveyFormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class SurveyFormController extends Controller
{
    /**
     * @param SurveyFormService $service
     */
    public function __construct(
        protected SurveyFormService $service
    ) {
    }

    /**
     * Display a listing of the resource.
     * @throws FatalErrorException
     */
    public function index(): JsonResponse
    {
        // check redis exist or not
        $response = json_decode(Redis::get('survey_form'));
        if (!$response) {
            $data = $this->service->get();
            $response = new UserSurveyResource($data);
            Redis::setex('survey_form', 3600, json_encode($response));
        }
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     * @throws FatalErrorException
     */
    public function store(StoreSurveyRequest $request): JsonResponse
    {
        $survey = $this->service->store($request->validated());
        return response()->json(new SurveyFormResource($survey), 201);
    }

    /**
     * @throws UnauthorizedException
     */
    public function show(SurveyForm $survey): JsonResponse
    {
        if ($survey->user_id != auth()->user()->id) {
            throw new UnauthorizedException("User not authorize!");
        }

        return response()->json(new SurveyFormResource($survey));
    }
}
