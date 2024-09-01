<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Server(url="http://localhost/api/admin-v1"),
 * @OA\Info(title="Four Delivery API", version="1.0.0")
 */


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
