<?php 


namespace App\Services;

use App\Integrations\SourceOrder;

class OrdersService
{

  private $source;

  public function __construct(SourceOrder $source)
  {
    $this->source = $source;
  }
  public function validateQuantityOrders($total,$qtdMax) : bool
  {
    if ($total > 0 && $total >= $qtdMax) {
        return false;
    }
    return true;
  }

  public function getQuantityOrders($clientId) : int
  {
    return $this->source->getQuantityOrders($clientId);
  }
}