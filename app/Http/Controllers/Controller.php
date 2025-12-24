<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Four Delivery API",
 *     version="1.0.0",
 *     description="API para o sistema de delivery Four Delivery",
 *     @OA\Contact(
 *         email="suporte@fourdelivery.com"
 *     )
 * )
 * @OA\Server(
 *     url="/api/admin-v1",
 *     description="Admin API V1"
 * )
 * @OA\Server(
 *     url="/api/app-v1",
 *     description="App API V1"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
