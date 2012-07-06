<?php

namespace RockPaperScissors;

interface Player {

    public function name();
    public function makeChoice();
    public function revealHand();

}