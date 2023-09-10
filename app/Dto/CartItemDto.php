<?php

namespace App\DTO;

class CartItemDto
{

  public $weight;
  public $price;
  public $quantity;


  public function __construct(array $data)
  {
    $this->weight = $data['weight'];
    $this->price = $data['price'];
    $this->quantity = $data['quantity'];
  }

  public function getWeight()
  {
    return $this->weight;
  }

  public function getPrice()
  {
    return $this->price;
  }

  public function getQuantity()
  {
    return $this->quantity;
  }

}