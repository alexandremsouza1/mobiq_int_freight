<?php

namespace App\Http\Controllers;

use App\Services\FreightService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FreightController extends DefaultApiController
{
    protected $service;


    public function __construct(FreightService $service)
    {
      $this->service = $service;
    }

    public function getFreight(Request $request): JsonResponse
    {
      $cartUuid = $request->get('cartUuid');
      $response = $this->service->getFreight($cartUuid);
      $messageText = 'Freight retrieved successfully';
      $statusCode = 200;
      return response()->json(['data' => $response,'message' => $messageText, 'status' => true], $statusCode);
    }
}
