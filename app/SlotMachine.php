<?php

namespace App;

class SlotMachine
{
    private int $rowAmount = 3;
    private int $userSelectsColumns = 4;
    private array $gameDeck;
    private array $slotSymbols = [" $ ", " % ", " @ "];
    private array $jackPotSymbol = [" 7 "];
    private array $winningCombinations = [
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

    public function multiplier(array $winComb, string $firstSymbol, array $specialSymbols): int
    {
        $jackPot = 1;
        if (in_array($firstSymbol, $specialSymbols)){
            $jackPot = 1000;
        }

        if (count($winComb) === 6){
            return $jackPot * 4096;
        }

        if (count($winComb) === 4){
            return $jackPot * 2;
        }

        if (count($winComb) === 3){
            return 1;
        }
    }

    public function getWinningCombinations(): array
    {
        return $this->winningCombinations;
    }

    /**
     * @return int
     */
    public function getRowAmount(): int
    {
        return $this->rowAmount;
    }

    /**
     * @return int
     */
    public function getUserSelectsColumns(): int
    {
        return $this->userSelectsColumns;
    }

    /**
     * @return array
     */
    public function getSlotSymbols(): array
    {
        return $this->slotSymbols;
    }

    /**
     * @return array
     */
    public function getJackPotSymbol(): array
    {
        return $this->jackPotSymbol;
    }

    /**
     * @param array $gameDeck
     */
    public function setGameDeck(): void
    {
        $this->gameDeck = $this->generateGameDeck();
    }


    public function getGameDeck(): array
    {
        return $this->gameDeck;
    }

    public function generateGameDeck(): array
    {
        $deck = [];
        for ($i = 0; $i < $this->getRowAmount(); $i++){
            $deck[] = [];
            for ($ix = 0; $ix < $this->getUserSelectsColumns(); $ix++){
                if (random_int(1, 7) === 7){
                    $deck[$i][] = $this->getJackPotSymbol()[0];
                } else {
                    $deck[$i][] = $this->getSlotSymbols()[random_int(0, count($this->getSlotSymbols()) - 1)];
                }
            }
        }

        return $deck;
    }

    public function checkWinner($deck){
        foreach ($this->getWinningCombinations() as $combination){

            [$startX, $startY] = $combination[0];
            $combinationCounter = 0;

            for ($z = 0; $z < count($combination); $z++){
                [$x, $y] = $combination[$z];
                if ($deck[$x][$y] !== $deck[$startX][$startY]){
                    break;
                }

                $combinationCounter++;
            }

            if ($combinationCounter === count($combination)){
                for ($z = 0; $z < count($combination); $z++){
                    [$x, $y] = $combination[$z];
                    $this->gameDeck[$x][$y] = "\033[31m\033[107m" . $deck[$x][$y] . "\033[0m";
                }
                return $combination;

            }
        }
        return null;
    }

}