<?php

namespace App\Service;

use App\Api\ApiConnector;
use App\Api\SoapConnector;
use App\Entity\Calculator;
use App\Helper\XlsxFileHelper;

class RpsnCalculatorService
{
    private const XLSX_FILE_RATES = 'http://www.toppojisteni.net/zadani/sazby.xlsx';

    /**
     * @var ApiConnector
     */
    private $apiConnector;

    /**
     * @var SoapConnector
     */
    private $soapConnector;

    /**
     * @var XlsxFileHelper
     */
    private $xlsxFileHelper;

    public function __construct(ApiConnector $apiConnector, SoapConnector $soapConnector, XlsxFileHelper $xlsxFileHelper)
    {
        $this->apiConnector = $apiConnector;
        $this->soapConnector = $soapConnector;
        $this->xlsxFileHelper = $xlsxFileHelper;
    }

    public function getCalculation(Calculator $calculator): array
    {
        $hash = $this->apiConnector->getHashByBirthNumber($calculator->getBirthNumber());
        $calculator->setHash($hash);

        $firstCalculator = $this->soapConnector->getRatesByData(clone $calculator);

        $calculatorSecond = clone $calculator;
        $resultSecond = $this->apiConnector->getRatesByRepaymentAndAmount($calculatorSecond->getRepaymentTime(), $calculatorSecond->getAmount());

        foreach ($resultSecond as $value) {
            if ($value['fix'] === $calculator->getFixationTime()) {
                $calculatorSecond->setRate($value['intRate']);
                $calculatorSecond->setRpsn($value['rpsn']);
                $resultSecond = $calculatorSecond;
                break;
            }
        }

        $resultThird = $this->xlsxFileHelper->analyzeFile(clone $calculator);
        return [1 => $firstCalculator, 2 => $resultSecond, 3 => $resultThird];
    }
}