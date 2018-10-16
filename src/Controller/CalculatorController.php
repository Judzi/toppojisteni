<?php

namespace App\Controller;

use App\Entity\Calculator;
use App\Exception\CalculatorException;
use App\Form\RpsnCalculatorType;
use App\Service\RpsnCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    /**
     * @var RpsnCalculatorService
     */
    private $calculatorService;

    public function __construct(RpsnCalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
    }

    /**
     * @Route("/calculator", name="calculator", methods={"GET", "POST"})
     */
    public function index(Request $request)
    {
        $results = [];
        $calculator = new Calculator();
        $form = $this->createForm(RpsnCalculatorType::class, $calculator);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $results = $this->calculatorService->getCalculation($calculator);
            } catch (CalculatorException $e) {
                $results = [];
            }
        }

        return $this->render('calculator/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
            'calculator' => $calculator
        ]);
    }
}
