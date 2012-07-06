<?php

namespace RockPaperScissors;

class HumanPlayer implements Player {

    private $name;
    private $choice;

    public function __construct($name){
        $this->name = $name;
    }

    public function name()
    {
        return $this->name;
    }

    public function choose($hand){
        $this->choice = $hand;
    }

    public function makeChoice()
    {
        // already done?
    }

    public function revealHand()
    {
        return $this->choice;
    }
}