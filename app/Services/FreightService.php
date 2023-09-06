<?php


namespace App\Services;

use App\DTO\CreditLimitDto;
use App\Factory\FactoryCreditLimitDto;
use App\Models\Rules;

class FreightService
{

    const DELIVERY_TYPES = [
        0 => 'conventional',
        1 => 'express',
        2 => 'urgent',
        3 => 'scheduled',
    ];

    /** @var FactoryCreditLimitDto $factory */
    protected $factory;

    /** @var CreditLimitDto $creditLimitDto */
    private $creditLimitDto;

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
        FactoryCreditLimitDto $factory,
        CartService $cartService,
        RulesService $rulesService,
        RulesItemService $rulesItemService,
        OrdersService $ordersService
    ) {
        $this->factory = $factory;
        $this->cartService = $cartService;
        $this->rulesService = $rulesService;
        $this->rulesItemService = $rulesItemService;
        $this->ordersService = $ordersService;
    }



    public function getFreight(string $cartUuid)
    {
        $this->cart = $this->cartService->getCart($cartUuid);
        $this->clientId = $this->cart->clientId;

        $this->creditLimitDto = $this->factory->createCreditLimitDto($this->clientId);
        $this->rulesItemService->setCoordinates($this->creditLimitDto->getCoordinates());
        $flexibleLogistics = $this->rules();
        return $flexibleLogistics;
    }

 
    public function rules()
    {
        $flexibleLogistics = [];
        $rules = $this->rulesService->getRules();
        $totalOrdersCount = $this->ordersService->getQuantityOrders($this->clientId);
        foreach ($rules as $rule) {
            $validateQuantityOrders = $this->ordersService->validateQuantityOrders($totalOrdersCount, $rule->qtde_pedido_maxima_frete);
            if(!$validateQuantityOrders) {
                continue;
            }
            $key = $rule->tipo_entrega;
            $result = $this->rulesItemService->getRulesItem($rule);
            if(!empty($result)) {
                $flexibleLogistics[] = $result;
                if ($key === 'expresso') {
                    if ($this->cart->total_price > $rule->pedido_minimo / 100) {
                      $flexibleLogistics['price'] = 0;
                    } else {
                      $flexibleLogistics['price'] = $rule->valor_frete;
                    }
                  }
            }
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
