<?php

namespace App\Integrations;


class SourceCart
{

  private $client;


  public function __construct()
  {
    $this->client = new Client(env('MICROSERVICE_CART_INTEGRATION_URL'));
  }

  public function getCart($clientId)
  {
    $url = 'carts/' . $clientId;
    $result = $this->client->get($url);
    return isset($result['data']) ? $result['data'] : [];
  }
}