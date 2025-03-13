<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="login",
     *     operationId="login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="test@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456789"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully created",
     *         @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="1|xQo1JJxQBnnzDOTISkSqMr7EzFbdL5sSepyt5X9qf879fb56"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="id", type="integer", example=1),
     *          )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error: Unprocessable Content",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="error message"),
     *             @OA\Property(property="errors", type="object", example="{}")
     *         )
     *     )
     * )
     */
    public function login(AuthRequest $request)
    {
        $result = $this->service->login($request->validated());
        if (isset($result['error'])) {
            return response()->json(["message" => $result['error']['message']], $result['error']['code']);
        } else {
            return response()->json($result);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="register",
     *     operationId="register",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="test@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456789"),
     *             @OA\Property(property="password_confirmation", type="string", example="123456789"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successfully created",
     *         @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="updated_at", type="string", example="2025-03-13T09:52:51.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2025-03-13T09:52:51.000000Z"),
     *              @OA\Property(property="id", type="integer", example=1),
     *          )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error: Unprocessable Content",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="error message"),
     *             @OA\Property(property="errors", type="object", example="{}")
     *         )
     *     )
     * )
     */
    public function register(AuthRequest $request)
    {
        return response()->json($this->service->register($request->validated()), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/auth/me",
     *     summary="my info",
     *     operationId="auth_me",
     *     security={{"bearer_token": {}}},
     *     tags={"Auth"},
     *     @OA\Response(
     *         response="200",
     *         description="Successfully created",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com"),
     *              @OA\Property(property="email_verified_at", type="string", example="null"),
     *              @OA\Property(property="created_at", type="string", example="2025-03-13T09:52:51.000000Z"),
     *              @OA\Property(property="updated_at", type="string", example="2025-03-13T09:52:51.000000Z"),
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
    public function me(Request $request)
    {
        return $request->user();
    }

    /**
     * @OA\Get(
     *     path="/api/v1/logout",
     *     summary="logout",
     *     operationId="logout",
     *     security={{"bearer_token": {}}},
     *     tags={"Auth"},
     *     @OA\Response(
     *         response="200",
     *         description="Successfully created",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User successfully logged out!"),
     *         )
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
    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User successfully logged out!',
        ]);
    }
}
