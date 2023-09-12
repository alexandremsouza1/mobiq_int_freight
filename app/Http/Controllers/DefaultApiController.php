<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Contracts\ControllerInterface;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Delivery Microservice",
 *      description="This repository contains a microservice that integrates with Delivery, a provider of digital payment methods. The microservice allows developers to seamlessly integrate Delivery's payment functionality into their applications",
 *      @OA\Contact(
 *          email="administrativo@mobiup.com.br"
 *      ),
 *      @OA\License(
 *          name="MIT License",
 *          url=""
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 */
abstract class DefaultApiController extends BaseController
{
}
