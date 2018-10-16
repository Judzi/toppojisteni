<?php

namespace App\Api;

use App\Entity\Calculator;
use Freshcells\SoapClientBundle\SoapClient\SoapClient;

class SoapConnector
{
    public function getRatesByData(Calculator $calculator): ?Calculator
    {
        $options = [
            'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
            'style'=>SOAP_RPC,
            'use'=>SOAP_ENCODED,
            'soap_version'=>SOAP_1_1,
            'cache_wsdl'=>WSDL_CACHE_NONE,
            'connection_timeout'=>15,
            'trace'=>true,
            'encoding'=>'UTF-8',
            'exceptions'=>true,
        ];

        $client = new SoapClient('http://www.toppojisteni.net/zadani/soap/server.php?wsdl', $options);
        try {
            $response = $client->__soapCall('Calc', [(object) [
                'clientScoringHash' => $calculator->getHash(),
                'amount' => $calculator->getAmount(),
                'house_value' => $calculator->getHouseValue(),
                'repayment_time' => $calculator->getRepaymentTime(),
                'fixation' => $calculator->getFixationTime()
            ]]);
            if ($response) {
                $calculator->setRpsn($response->rpsn);
                $calculator->setRate($response->interest_rate);

                return $calculator;
            }
            return null;
        } catch (\SoapFault $exception) {
            return null;
        }
    }
}