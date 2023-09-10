<?php


namespace App\Services;

use App\Models\Rules;
use Carbon\Carbon;

class FreightService
{

    const DELIVERY_TYPES = [
        0 => 'conventional',
        1 => 'express',
        2 => 'urgent',
        3 => 'scheduled',
    ];



    /** @var CartService $cartService */
    private $cartService;

    /** @var RulesService $rulesService */
    private $rulesService;

    /** @var RulesItemService $rulesItemService */
    private $rulesItemService;

    /** @var OrdersService $ordersService */
    private $ordersService;


    private $cart;

    private $clientId;


    public function __construct(
        CartService $cartService,
        RulesService $rulesService,
        RulesItemService $rulesItemService,
        OrdersService $ordersService
    ) {
        $this->cartService = $cartService;
        $this->rulesService = $rulesService;
        $this->rulesItemService = $rulesItemService;
        $this->ordersService = $ordersService;
    }



    public function getFreight(string $cartUuid)
    {
        $this->cart = $this->cartService->getCart($cartUuid);
        $this->clientId = $this->cart->clientId;
        $this->rulesItemService->setClientId($this->clientId);

        $flexibleLogistics = $this->rules();
        return $flexibleLogistics;
    }

 
    public function rules()
    {
        $flexibleLogistics = [];
        $entregaConvencional = $this->rulesItemService->fillEntregaConvencional($this->cart->getTotalPrice());
        $rules = $this->rulesService->getRules();
        $totalOrdersCount = $this->ordersService->getQuantityOrders($this->clientId);
        foreach ($rules as $key => $rule) {
            $deliveryType = self::DELIVERY_TYPES[$rule->tipo_entrega];
            $validateQuantityOrders = $this->ordersService->validateQuantityOrders($totalOrdersCount, $rule->qtde_pedido_maxima_frete);
            if(!$validateQuantityOrders) {
                continue;
            }
            $result = $this->rulesItemService->getRulesItem($rule,$this->cart);
            if(!empty($result)) {
                $flexibleLogistics[] = $result;
                if ($deliveryType === 'express') {
                    if ($this->cart->getTotalPrice() > $rule->pedido_minimo / 100) {
                      $flexibleLogistics[$key]['price'] = 0;
                    } else {
                      $flexibleLogistics[$key]['price'] = $rule->valor_frete;
                    }
                  }
            }
        }
        if(!empty($entregaConvencional)) {
            $flexibleLogistics[] = $entregaConvencional;
        }
        return $flexibleLogistics;
    }



    public function validateQuantityOrders(Rules $regra, $quantidade)
    {
        if ($quantidade > 0 && $quantidade >= $regra->getQtdePedidoMaximaFrete()) {
            return false;
        }
        return true;
    }
}
