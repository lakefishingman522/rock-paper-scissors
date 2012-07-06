<?php

namespace RockPaperScissors;

class Automaton implements Player {

    private $choice;
    private $name;

    public function __construct($name){
        $this->name = $name;
    }

    public function name()
    {
        return $this->name;
    }

    public function makeChoice()
    {
        $options = array(Game::PAPER, Game::ROCK, Game::SCISSORS);
        $this->choice = $options[array_rand($options, 1)];
    }

    public function revealHand()
    {
        return $this->choice;
    }
}