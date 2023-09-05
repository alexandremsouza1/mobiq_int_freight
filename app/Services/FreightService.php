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


    private $cart;


    public function __construct(
        FactoryCreditLimitDto $factory,
        CartService $cartService,
        RulesService $rulesService,
        RulesItemService $rulesItemService
    ) {
        $this->factory = $factory;
        $this->cartService = $cartService;
        $this->rulesService = $rulesService;
        $this->rulesItemService = $rulesItemService;
    }



    public function getFreight(string $cartUuid)
    {
        $this->cart = $this->cartService->getCart($cartUuid);
        $clientId = $this->cart->clientId;

        $this->creditLimitDto = $this->factory->createCreditLimitDto($clientId);
        $this->rulesItemService->setCoordinates($this->creditLimitDto->getCoordinates());
        $flexibleLogistics = $this->rules();
        return $flexibleLogistics;
    }

 
    public function rules2($tipo, $coordenadasCliente, $quantidade)
    {
        $previsaoEntrega = null;
        $previsaoEntregaSap = null;

        $deliveryTypes = [
            0 => 'conventional',
            1 => 'express',
            2 => 'urgent',
            3 => 'scheduled',
        ];
        $return = [];
        $return[] = [
            'deliveryName'             => 'Conventional Delivery',
            'deliveryTypeId'          => '',
            'deliveryEstimate'         => '',
            'deliveryEstimateSap'     => '',
            'id'                        => '',
            'businessHours'            => '',
            'businessHoursEnd'        => '',
            'minimumOrder'             => '',
            'key'                       => 'conventional',
            'cutOffTime'              => ''
        ];
        $regras = $this->rulesRepository->findAllByKey('tipo', $tipo);
        foreach ($regras as $r) {
            if ($r) {
                $dias = $r->prazo_dias;
                $horarioCorte = $r->horario_corte;
                if ($this->hasTimePassed($r->horario_corte)) {
                    $dias = intval($dias) + 1;
                    $horarioCorte = null;
                }

                date_default_timezone_set('America/Sao_Paulo');
                $dataInicial = new \Datetime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))));
                $dataFinal = new \Datetime(date('Y-m-d H:i:s', strtotime("+{$dias} days", strtotime(date('Y-m-d H:i:s')))));
                $dataInicialFinal = $this->nextBusinessDay($dataInicial, $dias, $dataFinal);


                $previsaoEntrega = $dataInicialFinal;
                $previsaoEntregaSap = $dataInicialFinal;

                $peso = $this->weightValueFreightRepository->findAllByKey('id_regra', $r->id);
                $pesoJson = $peso->toArray();
                $coordenadas = $this->polygonCoordinateRepository->findAllByKey('id_regra', $r->id);
                $coordenadasJson = $coordenadas->toArray();

                $poligonoString = [];
                $cont = 1;
                $inicioFim = '';
                $polygonCount = 0;
                if (is_array($coordenadasJson))
                    $polygonCount = count($coordenadasJson);
                $i = 0;
                foreach ($coordenadasJson as $key => $c) {
                    $coordenadasItens = $this->polygonCoordinateItemRepository->findAllByKey('id_coordenada', $c['id']);
                    $i = 0;
                    foreach ($coordenadasItens as $coordenadaItem) {
                        if ($cont == 1) {
                            $inicioFim = $coordenadaItem['latitude'] . ' ' . $coordenadaItem['longitude'];
                        }
                        $cont++;
                        $poligonoString[$key][] = trim($coordenadaItem['latitude']) . ' ' . trim($coordenadaItem['longitude']);
                        $i++;
                        //                        array_push($poligonoString[$key], trim($coordenadaItem['longitude']).','.trim($coordenadaItem['latitude']));
                    }
                }

                $poligonoString[$i][] =  $inicioFim;
                foreach ($poligonoString as $polygon) {
                    $checkInPolygon = $this->pointInPolygon($coordenadasCliente, $polygon);
                    if ($checkInPolygon)
                        break;
                }

                $validateQuantityOrders = $this->validateQuantityOrders($r, $quantidade);
                //                $verificaAbrangencia = true;
                // if ($verificaAbrangencia == true || ((isset($coordenadas) && $coordenadas->count() == 0) || (isset($coordenadasItens) && count($coordenadasItens) == 0))) {
                if ($checkInPolygon == false && $r->consider_polygon === '1') {
                    continue;
                }
                if ($validateQuantityOrders) {
                    $tipoEntrega = isset($deliveryTypes[$r->tipo_entrega]) ? $deliveryTypes[$r->tipo_entrega] : $deliveryTypes[0];
                    if ($tipo == 1) {
                        if (isset($pesoJson) && count($pesoJson) > 0) {
                            $return[] = [
                                'weight'                    => $pesoJson,
                                'coordinates'               => $coordenadasJson,
                                'deliveryName'             => $r->nome_entrega,
                                'deliveryEstimate'         => $previsaoEntrega,
                                'deliveryEstimateSap'     => $previsaoEntregaSap,
                                'id'                        => $r->id,
                                'deliveryTypeId'          => $r->id_tipo_entrega,
                                'businessHours'            => $r->horario_atendimento,
                                'businessHoursEnd'        => $r->horario_atendimento_fim,
                                'minimumOrder'             => null,
                                'key'                       => $tipoEntrega,
                                'cutOffTime'              => $horarioCorte
                            ];
                        } else {
                            $return[] = [
                                'weight'                    => [
                                    [
                                        'minimum_weight'    => 0,
                                        'maximum_weight'    => 1000000000000000000,
                                        'value'             => $r->valor_frete
                                    ]
                                ],
                                'coordinates'               => $coordenadasJson,
                                'deliveryName'             => $r->nome_entrega,
                                'deliveryEstimate'         => $previsaoEntrega,
                                'deliveryEstimateSap'     => $previsaoEntregaSap,
                                'id'                        => $r->id,
                                'deliveryTypeId'          => $r->tipo_entrega,
                                'businessHours'            => $r->horario_atendimento,
                                'businessHoursEnd'        => $r->horario_atendimento_fim,
                                'minimumOrder'             => (int)($r->pedido_minimo * 100),
                                'deliveryFee'              => $r->valor_frete,
                                'key'                       => $tipoEntrega,
                                'cutOffTime'              => $horarioCorte
                            ];
                        }
                    } else {
                        $return[] = [
                            'coordinates'               => $coordenadasJson,
                            'deliveryName'             => $r->nome_entrega,
                            'deliveryEstimate'         => $previsaoEntrega,
                            'deliveryEstimateSap'     => $previsaoEntregaSap,
                            'id'                        => $r->id,
                            'deliveryTypeId'          => $r->tipo_entrega,
                            'businessHours'            => $r->horario_atendimento,
                            'businessHoursEnd'        => $r->horario_atendimento_fim,
                            'minimumOrder'             => (int)($r->pedido_minimo * 100),
                            'deliveryFee'              => $r->valor_frete,
                            'key'                       => $tipoEntrega,
                            'cutOffTime'              => $horarioCorte
                        ];
                    }
                }
                // }
            }
        }
        return $return;
    }


    public function rules()
    {
        $flexibleLogistics = [];
        $rules = $this->rulesService->getRules();
        foreach ($rules as $rule) {
            $key = $rule->tipo_entrega;
            $flexibleLogistics[] = $this->rulesItemService->getRulesItem($rule);
            if ($key === 'expresso') {
              if ($this->cart->total_price > $rule->pedido_minimo / 100) {
                $flexibleLogistics['price'] = 0;
              } else {
                $flexibleLogistics['price'] = $rule->valor_frete;
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
