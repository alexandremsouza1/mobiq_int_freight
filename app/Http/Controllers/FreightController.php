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
      $clientId = $request->get('clientId');
      $orderCount =  $request->get('orderCount');
      $response = $this->service->getFreight($clientId,$orderCount);
      $messageText = 'Freight retrieved successfully';
      $statusCode = 200;
      return response()->json(['data' => $response,'message' => $messageText, 'status' => true], $statusCode);
    }
}
