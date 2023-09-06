<?php 


namespace App\Services;

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
    return $this->source->getCart($uuid);
  }
}