<?php

namespace App\Api;

use Http\Client\Exception;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;

class ApiConnector
{
    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var HttpClient
     */
    private $httpClient;


    public function __construct(MessageFactory $messageFactory, HttpClient $httpClient)
    {
        $this->messageFactory = $messageFactory;
        $this->httpClient = $httpClient;
    }

    public function getHashByBirthNumber(string $birthNumber): ?string
    {
        $request = $this->messageFactory->createRequest(
            'GET',
            'http://www.toppojisteni.net/zadani/rest/rc.php?rc=' . $birthNumber
        );

        $response = $this->parseResponse($this->sendRequest($request));

        return $response['hash'];
    }

    public function getRatesByRepaymentAndAmount(int $repaymentTime, int $amount): ?array
    {
        $request = $this->messageFactory->createRequest(
            'POST',
            'http://www.toppojisteni.net/zadani/rest/institution.php',
            [],
            json_encode([
                'Amount' => $amount,
                'RepTime' => $repaymentTime,
            ])
        );

        return current($this->parseResponse($this->sendRequest($request)));
    }

    private function sendRequest(RequestInterface $request): ?MessageInterface
    {
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (Exception $e) {
            $response = null;
        } catch (\Exception $e) {
            $response = null;
        }

        return $response;
    }

    private function parseResponse(MessageInterface $response): ?array
    {
        if (null !== $response) {
            return json_decode($response->getBody(), true);
        }
        return null;
    }
}