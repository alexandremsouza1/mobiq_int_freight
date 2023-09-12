<?php

namespace App\Http\Controllers;

use App\Services\DeliveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryController extends DefaultApiController
{
    protected $service;


    public function __construct(DeliveryService $service)
    {
      $this->service = $service;
    }
    /**
     * @OA\Get(
     *     path="/deliveries",
     *     summary="Get Delivery Information",
     *     description="Retrieve information about delivery for a specific cart.",
     *     tags={"Delivery"},
     *     @OA\Parameter(
     *         name="cartUuid",
     *         in="query",
     *         required=true,
     *         description="UUID of the cart for which to retrieve delivery information.",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="e6b74b4d-8e92-4e1e-978d-e69f0a5b0ef7"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="string", description="Delivery information"),
     *             @OA\Property(property="message", type="string", description="A success message"),
     *             @OA\Property(property="status", type="boolean", description="Status indicator (true for success)"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="A message describing the error"),
     *             @OA\Property(property="status", type="boolean", description="Status indicator (false for failure)"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="A message indicating the resource was not found"),
     *             @OA\Property(property="status", type="boolean", description="Status indicator (false for failure)"),
     *         )
     *     ),
     * )
     */
    public function getDelivery(Request $request): JsonResponse
    {
      $cartUuid = $request->get('cartUuid');
      $response = $this->service->getDelivery($cartUuid);
      $messageText = 'Delivery retrieved successfully';
      $statusCode = 200;
      return response()->json(['data' => $response,'message' => $messageText, 'status' => true], $statusCode);
    }
}
