<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cluedo Game</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    require_once __DIR__ . '/vendor/autoload.php';

    use Alexander\CluedoWeb\Game;

    $game = new Game();
    $game->start();
    ?>
</body>

</html>