<?php

namespace App\Integrations;

use Illuminate\Support\Facades\Http;

class Client {

  private $microservice_sap_integration_url;

  public function __construct()
  {
    $this->microservice_sap_integration_url = env('MICROSERVICE_SAP_INTEGRATION_URL');
  }


  public function post($endpoint, $data)
  {
    $response = Http::post($this->microservice_sap_integration_url . $endpoint, $data);
    return $response->json();
  }

  public function get($endpoint)
  {
    $response = Http::get($this->microservice_sap_integration_url . $endpoint);
    return $response->json();
  }

}