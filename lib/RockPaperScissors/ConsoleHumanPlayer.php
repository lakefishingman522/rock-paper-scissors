<?php

namespace RockPaperScissors;

class ConsoleHumanPlayer extends HumanPlayer {

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var Writer
     */
    private $writer;

    public function __construct(Reader $reader, Writer $writer){
        $writer->write("Choose a name for yourself:");
        $name = $reader->read();
        parent::__construct($name);
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function makeChoice(){
        $this->writer->write("Make your choice! [rock,paper,scissors]");
        $this->choose($this->reader->read());
    }

}