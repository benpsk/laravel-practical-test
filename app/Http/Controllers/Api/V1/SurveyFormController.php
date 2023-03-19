<?php

namespace App\Http\Controllers\Api\V1;

use App\Api\Exceptions\FatalErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSurveyRequest;
use App\Services\SurveyFormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        return $this->service->get();
    }

    /**
     * Store a newly created resource in storage.
     * @throws FatalErrorException
     */
    public function store(StoreSurveyRequest $request): JsonResponse
    {
        return $this->service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
