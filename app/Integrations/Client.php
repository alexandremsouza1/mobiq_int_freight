<?php

namespace App\Integrations;

use Illuminate\Support\Facades\Http;

class Client {

  private $url;

  public function __construct($url)
  {
    $this->url = $url;
  }


  public function post($endpoint, $data)
  {
    $response = Http::post($this->url . $endpoint, $data);
    return $response->json();
  }

  public function get($endpoint)
  {
    $response = Http::get($this->url . $endpoint);
    return $response->json();
  }

}