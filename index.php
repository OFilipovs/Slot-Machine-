<?php

require_once "app/SlotMachine.php";
use App\SlotMachine;

function displayBoard (array $symbols, $rows, $columns) {
    $rowBorder = "-";
    $topAndBottomBorders = "-==-";
    $slotColumnBorders = "|";
    echo str_repeat($topAndBottomBorders, ($columns * 2)-1) . PHP_EOL;
    $keyForGameDeck = 0;
    for ($i = 0; $i < ($rows * 2)-1; $i++){
        if (!($i % 2 === 0)){
            echo str_repeat($slotColumnBorders . "  $rowBorder  " . $slotColumnBorders, $columns) . PHP_EOL;
        } else {
            for ($ix = 0; $ix < $columns; $ix++){
                echo $slotColumnBorders . " " . $symbols[$keyForGameDeck][$ix] . " " . $slotColumnBorders;
            }
            $keyForGameDeck++;
            echo PHP_EOL;
        }
    }
    echo str_repeat($topAndBottomBorders, ($columns * 2)-1) . PHP_EOL;
}

$clientMoney = readline("Enter your stack size> ");

$slotMachine = new SlotMachine();

while ($clientMoney > 0){
    $clientBetSize = readline("Enter bet amount or enter 'quit': ");
    if ($clientBetSize === "quit"){
        echo "Bye!" . PHP_EOL;
        exit;
    }

    if ($clientBetSize > $clientMoney && $clientBetSize != 0){
        echo "Invalid bet size! Remaining stack: ". $clientMoney . PHP_EOL;
        break;
    }

    $slotMachine->setGameDeck();

    $winCheck = $slotMachine->checkWinner($slotMachine->getGameDeck());

    displayBoard($slotMachine->getGameDeck(), $slotMachine->getRowAmount(), $slotMachine->getUserSelectsColumns());
    if ($winCheck !== null){
        [$startX, $startY] = $winCheck[0];
        $prizeMoney = $clientBetSize * $slotMachine->multiplier($winCheck, $slotMachine->getGameDeck()[$startX][$startY], $slotMachine->getJackPotSymbol());
        echo "You Won $prizeMoney ! Time to lose." . PHP_EOL;
        $clientMoney += $prizeMoney;
    } else {

        echo "House always wins!" . PHP_EOL;
        $clientMoney -= $clientBetSize;
    }
    echo "Your stack size: " . $clientMoney . PHP_EOL;
}

if ($clientMoney === 0) {
    echo "No money left to lose. Do not tell your wife or husband!" . PHP_EOL;
} else {
    echo "Come back soon." . PHP_EOL;
}


