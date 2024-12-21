<?php

namespace Alexander\CluedoWeb;

use Alexander\CluedoWeb\Player;
use Alexander\CluedoWeb\Room;
use Alexander\CluedoWeb\Corridor;
use Alexander\CluedoWeb\Dice;
use Alexander\CluedoWeb\Cards;

class Game
{
    private array $players = [];
    //private Player $player;
    private Room $room;
    private Corridor $corridor;
    private Dice $dice;
    private Cards $cards;

    public function __construct()
    {
        session_start();
        $this->room = new Room();
        $this->dice = new Dice();
        $this->cards = new Cards();

        if (!isset($_SESSION['game_settings'])) {
            $this->settingMenu();

        }
        $this->createPlayers();

        if (!empty($this->players)) {
            $this->corridor = new Corridor($this->players);
        }

        $this->savePosition();
    }

    public function start(): void
    {
        //$this->resetGameSettings();

        if (isset($_SESSION['game_settings'])) {
            //$this->createPlayers();
            $this->settingGame();
        }
        //dd($_POST['entrance']);
        dd($this->players, $_POST['entrance']);

    }
    private function resetGameSettings(): void
    {
        unset($_SESSION['game_settings']);
        unset($_SESSION['player_position']);
        for ($i = 1; $i < 7; $i++) {
            unset($_SESSION["bot{$i}_position"]);
        }
        unset($_SESSION['dice_result']);
        unset($this->players);
        echo "<div>Настройки игры сброшены!</div>";
    }

    public function createPlayers(): void
    {
        $action = $_SESSION['game_settings'];
        $characters = $this->cards->getCharacters();
        $countCharacters = count($characters);

        foreach ($characters as $key => $character) {
            if ($character["name"] === $action['character']) {
                $player = new Player();
                $player->setName($character["name"]);
                $player->setRow($character["row"]);
                $player->setCol($character["col"]);
                $player->setCurrentPlayerIndex(0);
                $this->players[] = $player;

                $characters = array_values(array_filter($characters, fn($character) => $character['name'] !== $action['character']));
                $countCharacters = count($characters);
            }
        }

        for ($i = 1; $i < $action['num_players']; $i++) {
            $player = new Bot();
            $charactersNum = rand(0, $countCharacters - 1);

            $selectedCharacter = $characters[$charactersNum];

            $player->setName($selectedCharacter['name']);
            $player->setRow($selectedCharacter['row']);
            $player->setCol($selectedCharacter['col']);
            $player->setCurrentPlayerIndex($i);

            $this->players[] = $player;

            array_splice($characters, $charactersNum, 1);
            $countCharacters--;
        }
    }

    private function savePosition(): void
    {
        if (isset($_SESSION['player_position'])) {
            $this->players[0]->setPosition($_SESSION['player_position']);
        }
        for ($i = 1; $i < count($this->players); $i++) {
            $playerKey = "bot{$i}_position";
            if (isset($_SESSION[$playerKey])) {
                $this->players[$i]->setPosition($_SESSION[$playerKey]);
            }
        }

        if (isset($_SESSION['dice_result'])) {
            $this->dice->setDice($_SESSION['dice_result']);
        }
    }

    public function settingGame(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'] ?? '';
            $indexPlayer = (int) $_POST['index'];

            if ($_SESSION['game_settings']['currentMovePlayer'] !== 0) {
                $this->dice->setSession('bot_dice_result');
                $this->dice->rollTheDice();
                $steps = $this->dice->getResult();
                $botIndex = $_SESSION['game_settings']['currentMovePlayer'];

                $this->players[$botIndex]->makeMove($steps);

                if ($_SESSION['game_settings']['currentMovePlayer'] === count($this->players) - 1) {
                    $_SESSION['game_settings']['currentMovePlayer'] = 0;
                } else {
                    $_SESSION['game_settings']['currentMovePlayer']++;
                }
                unset($_SESSION['bot_dice_result']);
            }

            if ($action === 'move' && $_SESSION['game_settings']['currentMovePlayer'] === $indexPlayer) {
                $steps = $this->dice->getResult();
                $success = $this->players[0]->movePlayer($_POST, $steps);

                if (!$success) {
                    echo "<div class='error'>Невозможно сделать ход на указанное количество клеток!</div>";
                } else {
                    if ($_SESSION['game_settings']['currentMovePlayer'] === count($this->players) - 1) {
                        $_SESSION['game_settings']['currentMovePlayer'] = 0;
                    } else {
                        $_SESSION['game_settings']['currentMovePlayer']++;
                    }
                    unset($_SESSION['dice_result']);
                }
            } elseif ($action === 'rollDice' && $_SESSION['game_settings']['currentMovePlayer'] === $indexPlayer && empty($_SESSION['dice_result'])) {
                $this->dice->rollTheDice();
            }
            echo "<div class='dice-result'>Вы выбросили: {$this->dice->getCube1()} и {$this->dice->getCube2()}. Сумма: {$this->dice->getResult()}</div>";
        }
        $this->renderMap();
    }


    private function settingMenu(): void
    {
        $characters = $this->cards->getCharacters();

        echo "
    <form method='post'>
        <input type='hidden' name='action' value='startGame'>

        <label for='num_players'>Количество игроков:</label>
        <select id='num_players' name='num_players'>";
        for ($i = 2; $i <= 6; $i++) {
            echo "<option value='{$i}'>{$i}</option>";
        }
        echo "</select>

        <label for='mode'>Режим игры:</label>
        <select id='mode' name='mode'>
            <option value='bots'>С ботами</option>
            <option value='no_bots'>Без ботов</option>
        </select>

        <label for='charactersSelect'>Выберите персонажа:</label>
        <select id='charactersSelect' name='character'>";
        foreach ($characters as $key => $character) {
            echo "<option value='{$key}'>{$character['name']}</option>";
        }
        echo "
        </select>

        <button type='submit'>Создать</button>
    </form>";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'startGame') {
            $num_players = (int) $_POST['num_players'];
            $mode = $_POST['mode'];
            $characterIndex = (int) $_POST['character'];

            $_SESSION['game_settings'] = [
                'num_players' => $num_players,
                'mode' => $mode,
                'character' => $characters[$characterIndex]['name'],
                'currentMovePlayer' => 0
            ];

            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
    }


    private function renderMap(): void
    {
        echo "
        <form method='post' style='margin: 0;'>
            <input type='hidden' name='action' value='rollDice'>
            <button type='submit' class='dice'>Бросить</button>
        </form>";

        echo "<div class='map'>";

        $this->room->renderRooms();
        $this->corridor->spawnCorridor();

        echo "</div>";
    }
}