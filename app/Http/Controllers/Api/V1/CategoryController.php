<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $service){
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="categories list",
     *     operationId="categoriesList",
     *     security={{"bearer_token": {}}},
     *     tags={"Categories"},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *               type="array",
     *               @OA\Items(
     *                   type="object",
     *                   properties={
     *                       @OA\Property(property="id", type="integer", example="ID"),
     *                       @OA\Property(property="name", type="string", example="CATEGORIYA NOMI"),
     *                       @OA\Property(property="parent_id", type="integer", example="1"),
     *                       @OA\Property(property="category", type="object", example="{}"),
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
