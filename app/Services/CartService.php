<?php 


namespace App\Services;

use App\DTO\CartDto;
use App\Integrations\SourceCart;

class CartService
{

  private $source;

  public function __construct(SourceCart $source)
  {
    $this->source = $source;
  }
  public function getCart($clientId)
  {
    $result = $this->source->getCart($clientId);
    $converted = new CartDto($result);
    return $converted->getCartDto();
  }
}