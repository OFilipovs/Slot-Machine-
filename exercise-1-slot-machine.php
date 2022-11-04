<?php
$rowAmount = 3;
$userSelectsColumns = 4;
$rowBorder = "-";
$topAndBottomBorders = "-==-";
$slotColumnBorders = "|";

$slotSymbols = [" $ ", " % ", " @ "];
$jackPotSymbol = [" 7 "];

$winningCombinations = [
        [[0, 0], [1, 1], [2, 0], [0, 2], [2, 2], [1, 3]],
        [[0, 3], [1, 2], [2, 3], [0, 1], [2, 1], [1, 0]],
        [[0, 0], [1, 1], [1, 2], [2, 3]],
        [[2, 0], [1, 1], [1, 2], [0, 3]],
        [[0, 0], [0, 1], [0, 2], [0, 3]],
        [[1, 0], [1, 1], [1, 2], [1, 3]],
        [[2, 0], [2, 1], [2, 2], [2, 3]],
        [[0, 0], [1, 0], [2, 0]],
        [[0, 1], [1, 1], [2, 1]],
        [[0, 2], [1, 2], [2, 2]],
        [[0, 3], [1, 3], [2, 3]]
];

$winMultiplier= ["regularOne" => 2, "bigOne" => 4096, "freeSpin" => 1];


function multiplier(array $winComb, $firstSymbol, $specialSymbols){
    $jackPot = 1;
    if (in_array($firstSymbol, $specialSymbols)){
        $jackPot = 1000;
    }

    if (count($winComb) === 6){
        return $jackPot * 4096;
    } elseif (count($winComb) === 4){
        return $jackPot * 2;
    } elseif (count($winComb) === 3){
        return 1;
    }
}



echo "Enter your stack size: ";
$clientMoney = readline("> ");

while ($clientMoney > 0){
    $clientBetSize = readline("Enter bet amount or enter 'quit': ");
    $gameDeck = [];
    if ($clientBetSize === "quit"){
        echo "Bye!" . PHP_EOL;
        break;
    } else if ($clientBetSize <= $clientMoney){
        // uncomment below array for testing
/*        $gameDeck = [
            ["\033[36m\033[107m 7 \033[0m", "\033[36m\033[107m 7 \033[0m", "\033[36m\033[107m 7 \033[0m", "\033[36m\033[107m 7 \033[0m"],
            [" $ ", " @ ", " $ ", " % "],
            [" % ", " @ ", " $ ", " $ "]
        ];*/
        for ($i = 0; $i < $rowAmount; $i++){
            $gameDeck[] = [];
            for ($ix = 0; $ix < $userSelectsColumns; $ix++){
                    if (random_int(1, 7) === 7){
                        $gameDeck[$i][] = $jackPotSymbol[0];
                    } else {
                        $gameDeck[$i][] = $slotSymbols[random_int(0, count($slotSymbols) - 1)];
                    }
            }
        }

        $loser = true;
        foreach ($winningCombinations as $key => $combination){

            [$startX, $startY] = $combination[0];

            $combinationCounter = 0;


            for ($z = 0; $z < count($combination); $z++){
                [$x, $y] = $combination[$z];
                if ($gameDeck[$x][$y] !== $gameDeck[$startX][$startY]){

                    break;
                }
                $combinationCounter++;

            }

            if ($combinationCounter === count($combination)){
                for ($z = 0; $z < count($combination); $z++){
                    [$x, $y] = $combination[$z];
                    $gameDeck[$x][$y] = "\033[31m\033[107m" . $gameDeck[$x][$y] . "\033[0m";
                }

                echo str_repeat($topAndBottomBorders, ($userSelectsColumns * 2)-1) . PHP_EOL;
                $keyForGameDeck = 0;
                for ($i = 0; $i < ($rowAmount * 2)-1; $i++){
                    if (!($i % 2 === 0)){
                        echo str_repeat($slotColumnBorders . "  $rowBorder  " . $slotColumnBorders, $userSelectsColumns) . PHP_EOL;
                    } else {
                        for ($ix = 0; $ix < $userSelectsColumns; $ix++){
                            echo $slotColumnBorders . " " . $gameDeck[$keyForGameDeck][$ix] . " " . $slotColumnBorders;
                        }
                        $keyForGameDeck++;
                        echo PHP_EOL;
                    }
                }
                echo str_repeat($topAndBottomBorders, ($userSelectsColumns * 2)-1) . PHP_EOL;

                echo "You Won! Time to lose." . PHP_EOL;
                $clientMoney += $clientBetSize * multiplier($combination, $gameDeck[$startX][$startY], $jackPotSymbol);
                echo "Your stack size: " . $clientMoney . PHP_EOL;
                $loser = false;
                break;

            }
        }

        if ($loser){

            echo str_repeat($topAndBottomBorders, ($userSelectsColumns * 2)-1) . PHP_EOL;
            $keyForGameDeck = 0;
            for ($i = 0; $i < ($rowAmount * 2)-1; $i++){
                if (!($i % 2 === 0)){
                    echo str_repeat($slotColumnBorders . "  $rowBorder  " . $slotColumnBorders, $userSelectsColumns) . PHP_EOL;
                } else {
                    for ($ix = 0; $ix < $userSelectsColumns; $ix++){
                        echo $slotColumnBorders . " " . $gameDeck[$keyForGameDeck][$ix] . " " . $slotColumnBorders;
                    }
                    $keyForGameDeck++;
                    echo PHP_EOL;
                }
            }
            echo str_repeat($topAndBottomBorders, ($userSelectsColumns * 2)-1) . PHP_EOL;

            echo "House always wins!" . PHP_EOL;
            $clientMoney -= $clientBetSize;
            echo "Your stack size: " . $clientMoney . PHP_EOL;
        }

    } else {
        echo "Invalid bet size! Remaining stack: ". $clientMoney . PHP_EOL;
    }


}

if ($clientMoney === 0) {
    echo "No money left to lose. Do not tell your wife or husband!" . PHP_EOL;
} else {
    echo "Come back soon." . PHP_EOL;
}


