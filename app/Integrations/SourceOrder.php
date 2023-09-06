<?php

namespace App\Integrations;


class SourceOrder
{

  private $client;


  public function __construct()
  {
    $this->client = new Client(env('MICROSERVICE_ORDER_INTEGRATION_URL'));
  }

  public function getQuantityOrders($clientId)
  {
    $url = 'order/quantity/' . $clientId;
    $result = $this->client->get($url);
    return isset($result['data']) ? $result['data'] : [];
  }
}