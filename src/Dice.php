<?php

namespace Alexander\CluedoWeb;

class Dice
{
    private int $cube1 = 0;
    private int $cube2 = 0;
    private int $result = 0;

    public function rollTheDice(): void
    {
        $this->cube1 = rand(1, 6);
        $this->cube2 = rand(1, 6);
        $this->result = $this->cube1 + $this->cube2;

        $this->setSession('dice_result');
    }
    public function setSession($type)
    {
        $_SESSION[$type] = ['cube1' => $this->cube1, 'cube2' => $this->cube2, 'result' => $this->result];
    }

    public function setDice(array $resultDice): void
    {
        $this->cube1 = $resultDice['cube1'];
        $this->cube2 = $resultDice['cube2'];
        $this->result = $resultDice['result'];
    }

    public function getCube1(): int
    {
        return $this->cube1;
    }

    public function getCube2(): int
    {
        return $this->cube2;
    }

    public function getResult(): int
    {
        return $this->result;
    }

}