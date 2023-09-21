<?php

namespace App\DTO;

class CartDto
{

  public $id;
  public $customer = [];
  public $clientId;
  public $cartItems;

  private $totalWeight;
  private $totalPrice;


  public function __construct(array $data)
  {
    $this->id = $data['id'];
    $this->customer = $data['customer'];
    $this->clientId = $this->customer['clientId'];
    $this->cartItems = array_map(function($item){
      return new CartItemDto($item);
    }, $data['cartItems']);
    $this->calculateTotals($this->cartItems);
  }

  public function getCartDto()
  {
    return $this;
  }

  private function calculateTotals($items) 
  {
    $this->totalWeight = 0;
    foreach ($items as $item) {
      $this->totalWeight += $item->weight * $item->quantity;
      $this->totalPrice += $item->price * $item->quantity;
    }
  }

  public function getTotalWeight()
  {
    return $this->totalWeight;
  }

  public function getTotalPrice()
  {
    return $this->totalPrice;
  }

}