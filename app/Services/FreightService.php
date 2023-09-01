<?php


namespace App\Services;

use App\DTO\FreightDto;
use App\Integrations\Source;
use App\Models\Rules;
use App\Repositories\PolygonCoordinateItemRepository;
use App\Repositories\PolygonCoordinateRepository;
use App\Repositories\RulesRepository;
use App\Repositories\WeightValueFreightRepository;

class FreightService
{

    protected $source;

    protected $rulesRepository;

    protected $weightValueFreightRepository;

    protected $polygonCoordinateRepository;

    protected $polygonCoordinateItemRepository;

    public function __construct(
        Source $source,
        RulesRepository $rulesRepository,
        WeightValueFreightRepository $weightValueFreightRepository,
        PolygonCoordinateRepository $polygonCoordinateRepository,
        PolygonCoordinateItemRepository $polygonCoordinateItemRepository
    ) {
        $this->source = $source;
        $this->rulesRepository = $rulesRepository;
        $this->weightValueFreightRepository = $weightValueFreightRepository;
        $this->polygonCoordinateRepository = $polygonCoordinateRepository;
        $this->polygonCoordinateItemRepository = $polygonCoordinateItemRepository;
    }



    public function getFreight($clientId,$quantidade)
    {
        $type = 1;
        $latitude = null;
        $longitude = null;
        $coords = null;
        $creditLimitData = $this->source->getCreditLimit($clientId);
        if ($creditLimitData && $creditLimitData['Latitude'] && $creditLimitData['Longitude']) {
            $latitude = str_replace(",", ".", $creditLimitData['Latitude']);
            $longitude = str_replace(",", ".", $creditLimitData['Longitude']);
            $coords = $latitude . ' ' . $longitude;
        }
        $frete = $this->rules($type, $coords, $quantidade);
        return $frete;
    }

 
    public function rules($tipo, $coordenadasCliente, $quantidade)
    {
        $previsaoEntrega = null;
        $previsaoEntregaSap = null;

        $tiposEntregas = [
            0 => 'convencional',
            1 => 'expresso',
            2 => 'urgente',
            3 => 'programada',
        ];
        $return = [];
        $return[] = [
            'nome_entrega'              => 'Entrega Convencional',
            'id_tipo_entrega'           => '',
            'previsao_entrega'          => '',
            'previsao_entrega_sap'      => '',
            'id'                        => '',
            'horario_atendimento'       => '',
            'horario_atendimento_fim'   => '',
            'pedido_minimo'             => '',
            'key'                       => 'convencional',
            'horario_corte'             => ''
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
                    $tipoEntrega = isset($tiposEntregas[$r->tipo_entrega]) ? $tiposEntregas[$r->tipo_entrega] : $tiposEntregas[0];
                    if ($tipo == 1) {
                        if (isset($pesoJson) && count($pesoJson) > 0) {
                            $return[] = [
                                'peso'                      => $pesoJson,
                                'coordenadas'               => $coordenadasJson,
                                'nome_entrega'              => $r->nome_entrega,
                                'previsao_entrega'          => $previsaoEntrega,
                                'previsao_entrega_sap'      => $previsaoEntregaSap,
                                'id'                        => $r->id,
                                'id_tipo_entrega'           => $r->tipo_entrega,
                                'horario_atendimento'       => $r->horario_atendimento,
                                'horario_atendimento_fim'   => $r->horario_atendimento_fim,
                                'pedido_minimo'             => null,
                                'key'                       => $tipoEntrega,
                                'horario_corte'             => $horarioCorte
                            ];
                        } else {
                            $return[] = [
                                'peso'                      => [
                                    [
                                        "peso_minimo"   => 0,
                                        "peso_maximo"   => 1000000000000000000,
                                        "valor"         => $r->valor_frete
                                    ]
                                ],
                                'coordenadas'               => $coordenadasJson,
                                'nome_entrega'              => $r->nome_entrega,
                                'previsao_entrega'          => $previsaoEntrega,
                                'previsao_entrega_sap'      => $previsaoEntregaSap,
                                'id'                        => $r->id,
                                'id_tipo_entrega'           => $r->tipo_entrega,
                                'horario_atendimento'       => $r->horario_atendimento,
                                'horario_atendimento_fim'   => $r->horario_atendimento_fim,
                                'pedido_minimo'             => (int)($r->pedido_minimo * 100),
                                'valor_frete'               => $r->valor_frete,
                                'key'                       => $tipoEntrega,
                                'horario_corte'             => $horarioCorte
                            ];
                        }
                    } else {
                        $return[] = [
                            'coordenadas'               => $coordenadasJson,
                            'nome_entrega'              => $r->nome_entrega,
                            'previsao_entrega'          => $previsaoEntrega,
                            'previsao_entrega_sap'      => $previsaoEntregaSap,
                            'id'                        => $r->id,
                            'id_tipo_entrega'           => $r->tipo_entrega,
                            'horario_atendimento'       => $r->horario_atendimento,
                            'horario_atendimento_fim'   => $r->horario_atendimento_fim,
                            'pedido_minimo'             => (int)($r->pedido_minimo * 100),
                            'valor_frete'               => $r->valor_frete,
                            'key'                       => $tipoEntrega,
                            'horario_corte'             => $horarioCorte
                        ];
                    }
                }
                // }
            }
        }
        return $return;
    }

    private function hasTimePassed($specificTime)
    {
        $currentTime = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $specificDateTime = new \DateTime($specificTime, new \DateTimeZone('America/Sao_Paulo'));
        return $currentTime > $specificDateTime;
    }

    private function nextBusinessDay(\DateTime $dataAtual, int $prazoDias, \DateTime $dataFinal)
    {
        date_default_timezone_set('America/Sao_Paulo'); // seta o timezone para São Paulo

        $dataEntrega = clone $dataAtual;
        $dataEntrega->modify("+ $prazoDias days"); // adiciona dias úteis à data atual
        $feriados = $this->source->getConsultarFeriados($dataAtual->format('Y-m-d'), $dataFinal->format('Y-m-d')); // consulta os feriados
        foreach ($feriados as $feriado) {
            if ($feriado) {
                $dataEntrega->modify('+1 day'); // adiciona mais um dia útil
                return $this->nextBusinessDay($dataEntrega, 0, $dataEntrega); // chama a função recursivamente
            }
        }
        return $dataEntrega->format('Y-m-d H:i:s'); // retorna a data formatada como Y-m-d H:i:s
    }

    private function pointInPolygon($point, $polygon, $pointOnVertex = true)
    {
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = [];
        foreach ($polygon as $key => $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex);
        }

        // Check if the point sits exactly on a vertex
        if ($pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return true;
        }

        // Check if the point is inside the polygon or on the boundary
        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return true;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return true;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        // If the number of edges we passed through is odd, then it's in the polygon.
        if ($intersections % 2 != 0) {
            return true;
        } else {
            return false;
        }
    }

    private function pointOnVertex($point, $vertices)
    {
        foreach ($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }

    private function pointStringToCoordinates($pointString)
    {
        if ($pointString != "") {
            if (is_array($pointString))
                $pointString = $pointString[0];
            $coordinates = explode(" ", $pointString);
            return [
                "x" => $coordinates[0],
                "y" => $coordinates[1]
            ];
        } else {
            return [];
        }
    }

    public function validateQuantityOrders(Rules $regra, $quantidade)
    {
        if ($quantidade > 0 && $quantidade >= $regra->getQtdePedidoMaximaFrete()) {
            return false;
        }
        return true;
    }
}
