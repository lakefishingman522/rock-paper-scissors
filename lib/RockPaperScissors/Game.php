<?php

namespace RockPaperScissors;

class Game {

    const ROCK = 'rock';
    const SCISSORS = 'scissors';
    const PAPER = 'paper';

    private $validHands = array(
        self::PAPER,
        self::ROCK,
        self::SCISSORS
    );

    private $winner;
    private $draw = false;

    /**
     * @var Player
     */
    private $player1;

    /**
     * @var Player
     */
    private $player2;

    public function play(Player $player1, Player $player2){

        $this->player1 = $player1;
        $this->player2 = $player2;

        $player1->makeChoice();
        $player2->makeChoice();

        $hand1 = $player1->revealHand();
        $hand2 = $player2->revealHand();

        $comparison = $this->resolve($hand1, $hand2);

        if($comparison == 0){
            // it's a draw!
            $this->draw = true;
            $this->winner = null;
        } elseif($comparison == -1){
            // right
            $this->draw = false;
            $this->winner = $player1;
        } elseif($comparison == 1){
            // left
            $this->draw = false;
            $this->winner = $player2;
        }
    }

    public function hasWinner(){
        return null !== $this->winner();
    }

    public function winner(){
        return $this->winner;
    }

    public function isDraw(){
        return $this->draw;
    }

    public function replay(){
        if(null === $this->player1 || null === $this->player2){
            throw new Exception\UnplayedGame("You cannot replay a game you have not played yet.");
        }
        $this->play($this->player1, $this->player2);
    }

    /**
     * @throws Exception\UnplayedGame
     * @param Writer $writer
     * @return void
     */
    public function printResult(Writer $writer){
        if(!$this->isDraw() && !$this->hasWinner()){
            throw new Exception\UnplayedGame("You cannot print results for a game you have not played yet!");
        }

        $chosenTemplate =<<<CHOSEN
%s chose %s
%s chose %s
CHOSEN;


        $winTemplate =<<<WIN
%s beats %s
%s won
WIN;
        $drawTemplate = <<<DRAW
It's a draw
DRAW;

        $template = sprintf($chosenTemplate, $this->player1->name(), $this->player1->revealHand(), $this->player2->name(), $this->player2->revealHand());
        if($this->isDraw()){
            $writer->write($template ."\n". $drawTemplate);
        } else {
            $winner = $this->winner();
            $loser = $winner == $this->player1 ? $this->player2 : $this->player1;
            $writer->write($template ."\n". sprintf($winTemplate, $winner->revealHand(), $loser->revealHand(), $winner->name()));
        }
    }

    /**
     * @todo Probably a tidier way to do this really, perhaps mappings?
     * @param $hand1
     * @param $hand2
     * @return int -1,0,1 indicating whether left won, draw, or right won.
     */
    private function resolve($hand1, $hand2){

        // validate chosen options?
        $this->validateHand($hand1);
        $this->validateHand($hand2);

        switch(true){
            case $hand1 == self::PAPER && $hand2 == self::ROCK:
                return -1;
            case $hand1 == self::ROCK && $hand2 == self::ROCK:
                return 0;
            case $hand1 == self::SCISSORS && $hand2 == self::ROCK:
                return 1;
            case $hand1 == self::PAPER && $hand2 == self::PAPER:
                return 0;
            case $hand1 == self::ROCK && $hand2 == self::PAPER:
                return 1;
            case $hand1 == self::SCISSORS && $hand2 == self::PAPER:
                return -1;
            case $hand1 == self::PAPER && $hand2 == self::SCISSORS:
                return 1;
            case $hand1 == self::ROCK && $hand2 == self::SCISSORS:
                return -1;
            case $hand1 == self::SCISSORS && $hand2 == self::SCISSORS:
                return 0;
        }

    }

    /**
     * @throw Exception\InvalidHand
     */
    private function validateHand($hand){
        if(!in_array($hand, $this->validHands)){
            throw new Exception\InvalidHand("'$hand' is not a valid hand!");
        }
    }

}