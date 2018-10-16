<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Calculator
{
    /**
     * @var null|string
     */
    private $hash;

    /**
     * @var null|int
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 10000000
     * )
     */
    private $amount;

    /**
     * @var null|int
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 10000000
     * )
     */
    private $houseValue;

    /**
     * @var null|int
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 30
     * )
     */
    private $repaymentTime;

    /**
     * @var null|int
     *
     * @Assert\Choice({1, 3, 5, 10})
     */
    private $fixationTime;

    /**
     * @var null|float
     */
    private $rpsn;

    /**
     * @var null|string
     */
    private $birthNumber;

    /**
     * @var null|float
     */
    private $rate;

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getHouseValue(): ?int
    {
        return $this->houseValue;
    }

    public function setHouseValue(int $houseValue): void
    {
        $this->houseValue = $houseValue;
    }

    public function getRepaymentTime(): ?int
    {
        return $this->repaymentTime;
    }

    public function setRepaymentTime(int $repaymentTime): void
    {
        $this->repaymentTime = $repaymentTime;
    }

    public function getFixationTime(): ?int
    {
        return $this->fixationTime;
    }

    public function setFixationTime(int $fixationTime): void
    {
        $this->fixationTime = $fixationTime;
    }

    public function getRpsn(): ?float
    {
        return $this->rpsn;
    }

    public function setRpsn(float $rpsn): void
    {
        $this->rpsn = $rpsn;
    }

    public function getBirthNumber(): ?string
    {
        return $this->birthNumber;
    }

    public function setBirthNumber(?string $birthNumber): void
    {
        $this->birthNumber = $birthNumber;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): void
    {
        $this->rate = $rate;
    }
}
