<?php

namespace App\Integrations;


class Source
{

  private $client;


  public function __construct()
  {
    $this->client = new Client(env('MICROSERVICE_SAP_INTEGRATION_URL'));
  }

  public function getConditions($clientId)
  {
    $url = 'consultar-condicoes-pagamento?clientId=' . $clientId;
    $result = $this->client->get($url);
    return isset($result['data']) ? $result['data'] : [];
  }

  public function getCreditLimit($clientId)
  {
    $url = 'consultar-limite?clientId=' . $clientId;
    $result = $this->client->get($url);
    return isset($result['data']) ? $result['data'] : [];
  }

  public function getConsultarFeriados($dataInicial, $dataFinal)
  {
    $url = 'consultar-feriados?dataInicial=' . $dataInicial . '&dataFinal=' . $dataFinal;
    $result = $this->client->get($url);
    return isset($result['data']) ? $result['data'] : [];
  }
}