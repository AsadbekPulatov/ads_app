<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\RegionService;

class RegionController extends Controller
{
    protected RegionService $service;

    public function __construct(RegionService $service){
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/regions",
     *     summary="regions list",
     *     operationId="regionsList",
     *     security={{"bearer_token": {}}},
     *     tags={"Regions"},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               type="array",
     *               @OA\Items(
     *                   type="object",
     *                   properties={
     *                       @OA\Property(property="id", type="integer", example="ID"),
     *                       @OA\Property(property="name", type="string", example="REGION NOMI"),
     *                   }
     *               )
     *          )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json($this->service->list());
    }
}
