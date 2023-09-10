<?php

namespace App\DTO;

class CreditLimitDto
{
    private $channel;
    private $code;
    private $cokenet;
    private $visitDate;
    private $channelDescription;
    private $subchannelDescription;
    private $available;
    private $edi;
    private $deliveryD;
    private $deliveryOption1;
    private $deliveryOption2;
    private $financialStatement;
    private $phone1;
    private $phone1Vendor;
    private $phone1VendorPlanner;
    private $phone2;
    private $phone2Vendor;
    private $phone2VendorPlanner;
    private $cutoffTime;
    private $latitude;
    private $limit;
    private $longitude;
    private $message;
    private $minimumOrder;
    private $partners;
    private $subchannel;
    private $spentValue;
    private $salesman;
    private $salesmanPlanner;
    private $visit;
    private $visitKonnect;
    private $visitSales;
    private $visitPlanner;

    public function __construct(array $data)
    {
        $this->channel = $data['Canal'] ?? null;
        $this->code = $data['Codigo'] ?? null;
        $this->cokenet = $data['Cokenet'] ?? null;
        $this->visitDate = $data['Data_Visita'] ?? null;
        $this->channelDescription = $data['Desc_Canal'] ?? null;
        $this->subchannelDescription = $data['Desc_Subcanal'] ?? null;
        $this->available = $data['Disponivel'] ?? null;
        $this->edi = $data['EDI'] ?? null;
        $this->deliveryD = $data['Entrega_D'] ?? null;
        $this->deliveryOption1 = $data['Entrega_Opcao1'] ?? null;
        $this->deliveryOption2 = $data['Entrega_Opcao2'] ?? null;
        $this->financialStatement = $data['Ficha_Financeira'] ?? null;
        $this->phone1 = $data['Fone1'] ?? null;
        $this->phone1Vendor = $data['Fone1_Vendedor'] ?? null;
        $this->phone1VendorPlanner = $data['Fone1_Vendedor_Planner'] ?? null;
        $this->phone2 = $data['Fone2'] ?? null;
        $this->phone2Vendor = $data['Fone2_Vendedor'] ?? null;
        $this->phone2VendorPlanner = $data['Fone2_Vendedor_Planner'] ?? null;
        $this->cutoffTime = $data['Horario_Corte'] ?? null;
        $this->latitude = str_replace(",", ".", $data['Latitude']) ?? null;
        $this->limit = $data['Limite'] ?? null;
        $this->longitude = str_replace(",", ".", $data['Longitude']) ?? null;
        $this->message = $data['Mensagem'] ?? null;
        $this->minimumOrder = $data['Pedido_Minimo'] ?? null;
        $this->partners = $data['Socios'] ?? [];
        $this->subchannel = $data['Subcanal'] ?? null;
        $this->spentValue = $data['Valor_Gasto'] ?? null;
        $this->salesman = $data['Vendedor'] ?? null;
        $this->salesmanPlanner = $data['Vendedor_Planner'] ?? null;
        $this->visit = $data['Visita'] ?? null;
        $this->visitKonnect = $data['Visita_Konnect'] ?? null;
        $this->visitSales = $data['Visita_Vendas'] ?? null;
        $this->visitPlanner = $data['Visita_Planner'] ?? null;
    }

    // Getters para acessar os atributos

    public function getChannel()
    {
        return $this->channel;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getCokenet()
    {
        return $this->cokenet;
    }

    public function getVisitDate()
    {
        return $this->visitDate;
    }

    public function getChannelDescription()
    {
        return $this->channelDescription;
    }

    public function getSubchannelDescription()
    {
        return $this->subchannelDescription;
    }

    public function getAvailable()
    {
        return $this->available;
    }

    public function getEdi()
    {
        return $this->edi;
    }

    public function getDeliveryD()
    {
        return $this->deliveryD;
    }

    public function getDeliveryOption1()
    {
        return $this->deliveryOption1;
    }

    public function getDeliveryOption2()
    {
        return $this->deliveryOption2;
    }

    public function getFinancialStatement()
    {
        return $this->financialStatement;
    }

    public function getPhone1()
    {
        return $this->phone1;
    }

    public function getPhone1Vendor()
    {
        return $this->phone1Vendor;
    }

    public function getPhone1VendorPlanner()
    {
        return $this->phone1VendorPlanner;
    }

    public function getPhone2()
    {
        return $this->phone2;
    }

    public function getPhone2Vendor()
    {
        return $this->phone2Vendor;
    }

    public function getPhone2VendorPlanner()
    {
        return $this->phone2VendorPlanner;
    }

    public function getCutoffTime()
    {
        return $this->cutoffTime;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getMinimumOrder()
    {
        return $this->minimumOrder;
    }

    public function getPartners()
    {
        return $this->partners;
    }

    public function getSubchannel()
    {
        return $this->subchannel;
    }

    public function getSpentValue()
    {
        return $this->spentValue;
    }

    public function getSalesman()
    {
        return $this->salesman;
    }

    public function getSalesmanPlanner()
    {
        return $this->salesmanPlanner;
    }

    public function getVisit()
    {
        return $this->visit;
    }

    public function getVisitKonnect()
    {
        return $this->visitKonnect;
    }

    public function getVisitSales()
    {
        return $this->visitSales;
    }

    public function getVisitPlanner()
    {
        return $this->visitPlanner;
    }

    public function getCoordinates()
    {
        return [
            'latitude' => $this->getLatitude(),
            'longitude' => $this->getLongitude(),
        ];
    }

    public function toResult()
    {
        return [
            'channel' => $this->getChannel(),
            'code' => $this->getCode(),
            'cokenet' => $this->getCokenet(),
            'visitDate' => $this->getVisitDate(),
            'channelDescription' => $this->getChannelDescription(),
            'subchannelDescription' => $this->getSubchannelDescription(),
            'available' => $this->getAvailable(),
            'edi' => $this->getEdi(),
            'deliveryD' => $this->getDeliveryD(),
            'deliveryOption1' => $this->getDeliveryOption1(),
            'deliveryOption2' => $this->getDeliveryOption2(),
            'financialStatement' => $this->getFinancialStatement(),
            'phone1' => $this->getPhone1(),
            'phone1Vendor' => $this->getPhone1Vendor(),
            'phone1VendorPlanner' => $this->getPhone1VendorPlanner(),
            'phone2' => $this->getPhone2(),
            'phone2Vendor' => $this->getPhone2Vendor(),
            'phone2VendorPlanner' => $this->getPhone2VendorPlanner(),
            'cutoffTime' => $this->getCutoffTime(),
            'latitude' => $this->getLatitude(),
            'limit' => $this->getLimit(),
            'longitude' => $this->getLongitude(),
            'message' => $this->getMessage(),
            'minimumOrder' => $this->getMinimumOrder(),
            'partners' => $this->getPartners(),
            'subchannel' => $this->getSubchannel(),
            'spentValue' => $this->getSpentValue(),
            'salesman' => $this->getSalesman(),
            'salesmanPlanner' => $this->getSalesmanPlanner(),
            'visit' => $this->getVisit(),
            'visitKonnect' => $this->getVisitKonnect(),
            'visitSales' => $this->getVisitSales(),
            'visitPlanner' => $this->getVisitPlanner(),
        ];
    }
}
