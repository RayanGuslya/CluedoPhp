<?php

namespace Alexander\CluedoWeb;

class Bot extends Player
{
    public function makeMove(int $steps): void
    {
        $currentMovePlayer = $_SESSION['game_settings']['currentMovePlayer'];
        $currentPosition = $this->getCurrentPosition();
        $possibleMoves = $this->getPossibleMoves($currentPosition, $steps);

        if (!empty($possibleMoves)) {
            $chosenMove = $possibleMoves[array_rand($possibleMoves)];
            $this->setRow($chosenMove['row']);
            $this->setCol($chosenMove['col']);
;
        }
        unset($_SESSION['dice_result']);
    }

    private function getPossibleMoves(array $currentPosition, int $steps): array
    {
        $rows = 24;
        $cols = 25;
        $possibleMoves = [];

        for ($row = 1; $row <= $rows; $row++) {
            for ($col = 1; $col <= $cols; $col++) {
                if ($this->isValidMove($currentPosition, ['row' => $row, 'col' => $col], $steps)) {
                    $possibleMoves[] = ['row' => $row, 'col' => $col];
                }
            }
        }

        return $possibleMoves;
    }

    private function isValidMove(array $currentPosition, array $newPosition, int $steps): bool
    {
        $rowDistance = abs($currentPosition['row'] - $newPosition['row']);
        $colDistance = abs($currentPosition['col'] - $newPosition['col']);

        $distance = $rowDistance + $colDistance;
        if ($distance !== $steps) {
            return false;
        }

        if ($this->isObstacle($newPosition)) {
            return false;
        }

        return true;
    }

    private function isObstacle(array $position): bool
    {
        $row = $position['row'];
        $col = $position['col'];

        if (
            ($row >= 1 && $row <= 4 && $col >= 1 && $col <= 5) || // Kitchen
            ($row >= 1 && $row <= 5 && $col >= 8 && $col <= 18) || // Ballroom
            ($row >= 1 && $row <= 4 && $col >= 21 && $col <= 25) || // Conservatory
            ($row >= 7 && $row <= 16 && $col >= 1 && $col <= 7) || // Dining Room
            ($row >= 8 && $row <= 12 && $col >= 21 && $col <= 25) || // Billiard Room
            ($row >= 20 && $row <= 24 && $col >= 1 && $col <= 6) || // Lounge
            ($row >= 19 && $row <= 24 && $col >= 9 && $col <= 17) || // Hall
            ($row >= 20 && $row <= 24 && $col >= 20 && $col <= 25) || // Study
            ($row >= 14 && $row <= 17 && $col >= 20 && $col <= 25) || // Library
            ($row >= 8 && $row <= 17 && $col >= 10 && $col <= 17) // Cluedo
        ) {
            return true;
        }

        return false;
    }
}