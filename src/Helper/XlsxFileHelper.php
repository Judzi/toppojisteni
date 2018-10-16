<?php

namespace App\Helper;

use App\Entity\Calculator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class XlsxFileHelper
{
    private const AMOUNT_CELLS = [0, 5, 9, 13, 17];

    /**
     * @var string
     */
    private $publicRoot;

    public function __construct($rootDir)
    {
        $this->publicRoot = \dirname($rootDir) . '/public';
    }

    public function analyzeFile(Calculator $calculator): ?Calculator
    {
        $inputFileName = $this->publicRoot . '/sazby.xlsx';
        $sheet = $this->getSheetFromXlsx($inputFileName);
        $data = $sheet->toArray();

        foreach (self::AMOUNT_CELLS as $amountCell => $key) {
            if ($calculator->getAmount() <= $data[$amountCell][0] && $data[self::AMOUNT_CELLS[$key + 1]][0] > $calculator->getAmount()) {
                for ($y = $amountCell + 1; $y <= $amountCell + 3; $y++) {
                    if ((int) $data[$y][0] === $calculator->getFixationTime()) {
                        $fixTimeY = $y + 1;
                        break;
                    }
                }
                for ($x = $amountCell + 1; $x <= $amountCell + 6; $x++) {
                    if (
                        !empty($data[0][$x]) && (int) $data[0][$x] <= $calculator->getRepaymentTime() &&
                        (int) $data[0][$x + 2] > $calculator->getRepaymentTime()
                    ) {
                        $repTimeX = $x + 1;
                        break;
                    }
                }
                $rate = $sheet->getCellByColumnAndRow($repTimeX, $fixTimeY)->getValue();
                $rpsn = $sheet->getCellByColumnAndRow($repTimeX + 1, $fixTimeY)->getValue();

                if ($rate && $rpsn) {
                    $calculator->setRpsn($rpsn);
                    $calculator->setRate($rate);
                    return $calculator;
                    break;
                }
            }
        }
        return null;
    }

    private function getSheetFromXlsx(string $file): ?Worksheet
    {
        try {
            $reader = IOFactory::createReader('Xlsx');
        } catch (Exception $e) {
            return null;
        }
        try {
            $spreadsheet = $reader->load($file);
        } catch (Exception $e) {
            return null;
        }
        try {
            $sheet = $spreadsheet->getActiveSheet();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return null;
        }

        return $sheet;
    }
}