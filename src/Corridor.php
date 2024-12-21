<?php

namespace Alexander\CluedoWeb;

use Alexander\CluedoWeb\Player;

class Corridor
{
    private array $players;


    public function __construct(array $players)
    {
        $this->players = $players;
    }

    public function spawnCorridor(): void
    {
        $rows = 24;
        $cols = 25;

        $steps = $_SESSION['dice_result']['result'] ?? 0;

        for ($row = 1; $row <= $rows; $row++) {
            for ($col = 1; $col <= $cols; $col++) {
                $currentIndex = -1;
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
                    continue;
                }
                for ($i = 0; $i < count($this->players); $i++) {

                    $currentPosition = $this->players[$i]->getCurrentPosition();

                    if ($row === $currentPosition['row'] && $col === $currentPosition['col']) {
                        $currentIndex = $this->players[$i]->getCurrentPlayerIndex();
                    }
                }
                if ($currentIndex !== -1) {
                    //error_log($row . " " . $col);
                    echo "<div class='player number{$currentIndex}' style='grid-row: {$row}; grid-column: {$col};'></div>";

                } else {
                    echo "
                <form method='post' style='margin: 0;' onsubmit='return validateSteps(this);'>
                    <input type='hidden' name='row' value='{$row}'>
                    <input type='hidden' name='col' value='{$col}'>
                    <input type='hidden' name='action' value='move'>
                    <input type='hidden' name='index' value='0'>
                    <input type='hidden' name='steps' value='{$steps}'>
                    <button type='submit' class='corridor' style='grid-row: {$row}; grid-column: {$col};'></button>
                </form>";

                }
            }
        }
    }
}
?>