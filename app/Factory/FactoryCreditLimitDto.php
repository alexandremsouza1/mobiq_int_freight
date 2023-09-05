<?php

namespace App\Factory;

use App\DTO\CreditLimitDto;
use App\Integrations\Source;

class FactoryCreditLimitDto {

  private $source;

  public function __construct(Source $source) {
    $this->source = $source;
  }

  public function createCreditLimitDto(string $clientId) : CreditLimitDto
  {
    $creditLimitData = $this->source->getCreditLimit($clientId);
    return new CreditLimitDto($creditLimitData);
  }

}