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
  public function getCart($uuid)
  {
    $result = $this->source->getCart($uuid);
    $converted = new CartDto($result);
    return $converted->getCartDto();
  }
}