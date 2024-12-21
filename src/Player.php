<?php

namespace Alexander\CluedoWeb;

class Player
{
    protected int $row;
    protected int $col;
    protected string $name;
    private int $currentPlayerIndex = 0;
    private array $cards = []; // Карты игрока

    public function addCard(string $card): void
    {
        $this->cards[] = $card; // Добавляем карту в руку игрока
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function movePlayer(array $data, int $steps): bool
    {
        $newRow = (int) ($data['row'] ?? $this->row);
        $newCol = (int) ($data['col'] ?? $this->col);

        $distance = abs($newRow - $this->row) + abs($newCol - $this->col);

        if ($distance === $steps || $distance <= $steps) {
            $this->row = $newRow;
            $this->col = $newCol;

            $this->setSession('player_position');
            return true;
        }
        return false;
    }
    public function setSession($type)
    {
        $_SESSION[$type] = ['row' => $this->row, 'col' => $this->col];
    }

    public function setPosition(array $position): void
    {
        $this->row = $position['row'];
        $this->col = $position['col'];
    }

    public function getCurrentPosition(): array
    {
        return ['row' => $this->row, 'col' => $this->col];
    }
    public function setCurrentPlayerIndex(int $currentPlayerIndex): void
    {
        $this->currentPlayerIndex = $currentPlayerIndex;
    }
    public function getCurrentPlayerIndex(): int
    {
        return $this->currentPlayerIndex;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    public function setCol(int $col): void
    {
        $this->col = $col;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}