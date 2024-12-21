<?php

namespace Alexander\CluedoWeb;

class Cards
{
    private array $rooms =
        [
            'kitchen' => [
                'entrance' => [
                    [
                        'row' => 4,
                        'col' => 4,
                        'col2' => 4
                    ]
                ]
            ],
            'conservatory' => [
                'entrance' => [
                    'row' => 4,
                    'col' => 21,
                    'col2' => 21
                ]
            ],
            'ballRoom' => [
                'entrance' => [
                    ['row' => 3, 'col' => 18, 'col2' => 18],
                    ['row' => 5, 'col' => 17, 'col2' => 17],
                    ['row' => 5, 'col' => 9, 'col2' => 9],
                    ['row' => 3, 'col' => 8, 'col2' => 8]
                ]
            ],
            'billiardRoom' => [
                'entrance' => [
                    ['row' => 9, 'col' => 21, 'col2' => 21],
                    ['row' => 12, 'col' => 25, 'col2' => 25]
                ]
            ],
            'libary' => [
                'entrance' => [
                    ['row' => 14, 'col' => 23, 'col2' => 23],
                    ['row' => 15, 'col' => 20, 'col2' => 20]
                ]
            ],
            'study' => [
                'entrance' => ['row' => 20, 'col' => 20, 'col2' => 20]
            ],
            'hall' => [
                'entrance' => [
                    ['row' => 19, 'col' => 12, 'col2' => 15],
                    ['row' => 21, 'col' => 17, 'col2' => 17]
                ]
            ],
            'lounge' => [
                'entrance' => ['row' => 20, 'col' => 6, 'col2' => 6]
            ],
            'diningRoom' => [
                'entrance' => [
                    ['row' => 10, 'col' => 7, 'col2' => 7],
                    ['row' => 16, 'col' => 6, 'col2' => 6]
                ]
            ],
            'cluedo' => [
            ]
        ];

    private array $weapon = [
        "spanner" => "гаечный ключ",
        "candlestick" => "подсвечник",
        "dagger" => "кинжал",
        "lead pipe" => "свинцовая труба",
        "revolver" => "револьвер",
        "rope" => "веревка"
    ];

    private array $characters = [
        ["characters" => "doktor_orchid", "name" => "Доктор Орчид", "row" => 1, "col" => 7],
        ["characters" => "missis_peacock", "name" => "Миссис Пикок", "row" => 1, "col" => 19],
        ["characters" => "missis_scarlet", "name" => "Миссис Скарлет", "row" => 18, "col" => 1],
        ["characters" => "colonel_mustard", "name" => "Полковник Мастард", "row" => 18, "col" => 25],
        ["characters" => "professor_plum", "name" => "Профессор Плам", "row" => 5, "col" => 25],
        ["characters" => "reverend_green", "name" => "Преподобный Грин", "row" => 24, "col" => 7]
    ];

    private array $evidenceCards = [
        ["name" => "Extra Move", "description" => "Позволяет сделать дополнительный ход.", "action" => "extra_move"],
        ["name" => "Reveal Card", "description" => "Посмотрите одну карту у любого игрока.", "action" => "reveal_card"],
        ["name" => "Block Move", "description" => "Блокируйте ход другого игрока.", "action" => "block_move"],
        ["name" => "Free Room Entry", "description" => "Позволяет войти в любую комнату.", "action" => "free_room_entry"],
        ["name" => "Skip Turn", "description" => "Пропустите ход другого игрока.", "action" => "skip_turn"],
        ["name" => "Swap Position", "description" => "Обменяйтесь местами с другим игроком.", "action" => "swap_position"]
    ];

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function getWeapons(): array
    {
        return $this->weapon;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

    public function getAllCards(): array
    {
        return array_merge($this->rooms, $this->weapon, $this->characters); 
    }
}