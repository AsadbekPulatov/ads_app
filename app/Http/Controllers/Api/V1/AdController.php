<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ADRequest;
use App\Models\Ad;
use App\Services\ADService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    protected ADService $service;

    public function __construct(ADService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/ads",
     *     summary="Get filtered ads",
     *     description="Filter ads by various criteria such as search, categories, tags, regions, price, date, and sorting options.",
     *     operationId="getFilteredAds",
     *     tags={"Ads"},
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search ads by keyword",
     *         required=false,
     *         @OA\Schema(type="string", example="laptop")
     *     ),
     *     @OA\Parameter(
     *         name="categories[]",
     *         in="query",
     *         description="Filter by category IDs (array)",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="tags[]",
     *         in="query",
     *         description="Filter by tag IDs (array)",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="regions[]",
     *         in="query",
     *         description="Filter by region IDs (array)",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         name="price_from",
     *         in="query",
     *         description="Minimum price(10.00) filter (required if price_to is provided)",
     *         required=false,
     *         @OA\Schema(type="number", format="decimal")
     *     ),
     *     @OA\Parameter(
     *         name="price_to",
     *         in="query",
     *         description="Maximum price(10.00) filter (required if price_from is provided)",
     *         required=false,
     *         @OA\Schema(type="number", format="decimal")
     *     ),
     *     @OA\Parameter(
     *         name="date_from",
     *         in="query",
     *         description="Filter ads from this date (YYYY-MM-DD) (required if date_to is provided)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="date_to",
     *         in="query",
     *         description="Filter ads to this date (YYYY-MM-DD) (required if date_from is provided)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="order_column",
     *         in="query",
     *         description="Column to sort by (required if order_type is provided)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"price", "views", "created_at"})
     *     ),
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         description="Sorting order (asc or desc) (required if order_column is provided)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of ads per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="Successful response",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="current_page", type="integer", example=1),
     *          @OA\Property(
     *              property="data",
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="title", type="string", example="Awesome product"),
     *                  @OA\Property(property="description", type="string", example="This is a great ad"),
     *                  @OA\Property(property="price", type="number", format="float", example=99.99),
     *                  @OA\Property(property="views", type="integer", example=150),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-14T12:34:56Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-14T12:40:00Z")
     *              )
     *          ),
     *          @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/v1/ads?page=1"),
     *          @OA\Property(property="from", type="integer", nullable=true, example=null),
     *          @OA\Property(property="last_page", type="integer", example=1),
     *          @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/v1/ads?page=1"),
     *          @OA\Property(
     *              property="links",
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="url", type="string", nullable=true, example=null),
     *                  @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                  @OA\Property(property="active", type="boolean", example=false)
     *              )
     *          ),
     *          @OA\Property(property="next_page_url", type="string", nullable=true, example=null),
     *          @OA\Property(property="path", type="string", example="http://localhost:8000/api/v1/ads"),
     *          @OA\Property(property="per_page", type="integer", example=10),
     *          @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
     *          @OA\Property(property="to", type="integer", nullable=true, example=null),
     *          @OA\Property(property="total", type="integer", example=0)
     *      )
     *    ),
     *    @OA\Response(
     *          response="422",
     *          description="Error: Unprocessable Content",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="error message"),
     *              @OA\Property(property="errors", type="object", example="{}")
     *          )
     *    )
     * )
     */
    public function index(ADRequest $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->service->list($request->validated());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/ads",
     *     summary="Create a new ad",
     *     description="Creates a new advertisement with the given details",
     *     security={{"bearer_token": {}}},
     *     tags={"Ads"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "description", "image", "price", "categories[]", "regions[]", "tags[]"},
     *                 @OA\Property(property="title", type="string", maxLength=100, example="Awesome product"),
     *                 @OA\Property(property="description", type="string", example="This is a great ad"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Upload an image file"),
     *                 @OA\Property(property="price", type="string", example="99.99"),
     *                 @OA\Property(
     *                     property="categories[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=1)
     *                 ),
     *                 @OA\Property(
     *                     property="regions[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=2)
     *                 ),
     *                 @OA\Property(
     *                     property="tags[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *            response="201",
     *            description="Created",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Ad created successfully."),
     *            )
     *      ),
     *     @OA\Response(
     *           response="422",
     *           description="Error: Unprocessable Content",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="error message"),
     *               @OA\Property(property="errors", type="object", example="{}")
     *           )
     *     )
     * )
     */


    public function store(ADRequest $request)
    {
        $this->service->store($request->validated());
        return response()->json(["message" => "Ad created successfully."], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/ads/{id}",
     *     summary="Get ad details",
     *     description="Fetches details of a specific ad by its ID",
     *     tags={"Ads"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Ad ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *          @OA\Response(
     *          response=200,
     *          description="Ad details retrieved successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=3),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="title", type="string", example="my add edit 3"),
     *              @OA\Property(property="description", type="string", example="my text sadadwdad edit"),
     *              @OA\Property(property="image", type="string", example="ads/nezjUyWo2wHUpJL9bNgu4EL2sG3Kqsi2SkoOg4UL.png"),
     *              @OA\Property(property="price", type="string", example="10.23"),
     *              @OA\Property(property="views", type="integer", example=4),
     *              @OA\Property(property="status", type="integer", example=1),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-14T04:24:51.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-14T06:43:46.000000Z"),
     *              @OA\Property(property="regions", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=3),
     *                      @OA\Property(property="name", type="string", example="Navoiy"),
     *                      @OA\Property(property="created_at", type="string", nullable=true),
     *                      @OA\Property(property="updated_at", type="string", nullable=true),
     *                      @OA\Property(property="pivot", type="object",
     *                          @OA\Property(property="ad_id", type="integer", example=3),
     *                          @OA\Property(property="region_id", type="integer", example=3)
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="categories", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=2),
     *                      @OA\Property(property="name", type="string", example="Sub Cat 1"),
     *                      @OA\Property(property="parent_id", type="integer", example=1),
     *                      @OA\Property(property="pivot", type="object",
     *                          @OA\Property(property="ad_id", type="integer", example=3),
     *                          @OA\Property(property="category_id", type="integer", example=2)
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="tags", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=2),
     *                      @OA\Property(property="name", type="string", example="tag2"),
     *                      @OA\Property(property="created_at", type="string", nullable=true),
     *                      @OA\Property(property="updated_at", type="string", nullable=true),
     *                      @OA\Property(property="pivot", type="object",
     *                          @OA\Property(property="ad_id", type="integer", example=3),
     *                          @OA\Property(property="tag_id", type="integer", example=2)
     *                      )
     *                  )
     *              ),
     *              @OA\Property(property="path", type="string", example="http://localhost:8000/storage/ads/nezjUyWo2wHUpJL9bNgu4EL2sG3Kqsi2SkoOg4UL.png")
     *          )
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="Ad not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Not found")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        $result = $this->service->show($id);
        if (isset($result['error'])) {
            return response()->json(["message" => $result['error']["message"]], $result['error']['code']);
        } else
            return response()->json($result);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/ads/{id}",
     *     summary="Update Ad",
     *     description="Update Ad details",
     *     security={{"bearer_token": {}}},
     *     tags={"Ads"},
     *          @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ad ID",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", maxLength=100, example="Awesome product"),
     *                 @OA\Property(property="description", type="string", example="This is a great ad"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Upload an image file"),
     *                 @OA\Property(property="price", type="string", example="99.99"),
     *                 @OA\Property(
     *                     property="categories[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=1)
     *                 ),
     *                 @OA\Property(
     *                     property="regions[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=2)
     *                 ),
     *                 @OA\Property(
     *                     property="tags[]",
     *                     type="array",
     *                     @OA\Items(type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *            response="200",
     *            description="Success",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Successfully updated ad."),
     *            )
     *      ),
     *      @OA\Response(
     *             response="403",
     *             description="Forbidden",
     *             @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Forbidden"),
     *             )
     *       ),
     *       @OA\Response(
     *              response="404",
     *              description="Not Found",
     *              @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Not Found"),
     *              )
     *        ),
     *     @OA\Response(
     *           response="422",
     *           description="Error: Unprocessable Content",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="error message"),
     *               @OA\Property(property="errors", type="object", example="{}")
     *           )
     *     )
     * )
     */

    public function update(ADRequest $request, $id)
    {
        $result = $this->service->update($request->validated(), $id);
        if (isset($result['error'])) {
            return response()->json(["message" => $result['error']["message"]], $result['error']['code']);
        } else
            return response()->json(["message" => "Successfully updated ad."]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/ads/{id}",
     *     summary="Delete Ad",
     *     description="Delete Ad",
     *     security={{"bearer_token": {}}},
     *     tags={"Ads"},
     *          @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ad ID",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\Response(
     *            response="200",
     *            description="Success",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Successfully deleted ad."),
     *            )
     *      ),
     *      @OA\Response(
     *             response="403",
     *             description="Forbidden",
     *             @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Forbidden"),
     *             )
     *       ),
     *       @OA\Response(
     *              response="404",
     *              description="Not Found",
     *              @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Not Found"),
     *              )
     *        ),
     * )
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);
        if (isset($result['error'])) {
            return response()->json(["message" => $result['error']["message"]], $result['error']['code']);
        } else
            return response()->json(["message" => "Successfully deleted ad."]);
    }
}
