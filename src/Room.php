<?php

namespace Alexander\CluedoWeb;

use Alexander\CluedoWeb\Cards;

class Room
{
    private Cards $cards;
    private array $rooms;

    public function __construct()
    {
        $this->cards = new Cards();
        $this->rooms = $this->cards->getRooms();
    }

    public function renderRooms(): void
    {
        foreach ($this->rooms as $key => $roomName) {
            echo "<div class='room {$key}'>$key</div>";
        }
        $this->renderEntrance();
    }

    private function renderEntrance(): void
    {
        $steps = $_SESSION['dice_result']['result'] ?? 0;

        foreach ($this->rooms as $roomName => $room) {
            if (!isset($room['entrance'])) {
                continue;
            }

            $entrances = $room['entrance'];

            if (!is_array(reset($entrances))) {
                $entrances = [$entrances];
            }

            foreach ($entrances as $key => $coord) {
                $row = $coord['row'] ?? 0;
                $col = $coord['col'] ?? 0;
                $col2 = $coord['col2'] ?? $col;

                echo "
                <form method='post' style=' 
                margin: 0; 
                grid-column-start: {$col}; 
                grid-column-end: {$col2}; 
                grid-row-start: {$row}; 
                z-index: 10;' 
                onsubmit='return validateSteps(this);'>
                    <input type='hidden' name='row' value='{$row}'>
                    <input type='hidden' name='col' value='{$col}'>
                    <input type='hidden' name='action' value='move'>
                    <input type='hidden' name='index' value='0'>
                    <input type='hidden' name='stepsEntrance' value='{$steps}'>
                    <input type='hidden' name='entrance' value='{$roomName}_{$key}'>
                    <button type='submit' class='entrance'></button>
                </form>";
            }
        }
    }

    public function getRoomsWithEntrances(): array
    {
        return $this->rooms;
    }
}