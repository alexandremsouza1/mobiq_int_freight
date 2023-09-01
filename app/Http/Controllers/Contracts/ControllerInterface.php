<?php

namespace App\Http\Controllers\Contracts;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface ControllerInterface
{
  public function index(): JsonResponse;
  public function create(Request $request): JsonResponse;
  public function find($id): JsonResponse;
  public function update(Request $request, $id): JsonResponse;
}
