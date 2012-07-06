<?php

use RockPaperScissors\IO;
use RockPaperScissors\HumanPlayer;
use RockPaperScissors\ConsoleHumanPlayer;
use RockPaperScissors\Game;

class HumanPlayerTest extends PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    public function whenIRevealMyHandMyChoiceIsApparent(){

        $player = new HumanPlayer("James");

        // exec
        $player->choose(Game::ROCK);

        // assert
        $this->assertEquals(Game::ROCK, $player->revealHand());
    }


    /**
     * @test
     */
    public function consoleHumanPlayerPromptsDuringInstantiationAndMakingAChoice(){

        $descriptors = array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','a'));
        $include = realpath(__DIR__ . "/../bootstrap.php");
        $exec = "php -r 'include_once \"$include\";
        use RockPaperScissors\\IO;
        use RockPaperScissors\\ConsoleHumanPlayer;
        \$io = new IO();
        \$human = new ConsoleHumanPlayer(\$io, \$io);
        \$human->makeChoice();
        echo \$human->revealHand();
        '";
        $proc = proc_open($exec, $descriptors, $pipes);

        $out = $pipes[1];
        $in = $pipes[0];

        $line = fread($out, 1024);
        $this->assertEquals("Choose a name for yourself:\n", $line);
        // Perform name choosing.
        fwrite($in, "James");

        // expect question prompt for choice
        $line = fread($out, 1024);
        $this->assertEquals("Make your choice! [rock,paper,scissors]\n", $line);

        // input choice of paper
        fwrite($in, "paper");

        // expect "paper" echo'd back at me.
        $this->assertEquals("paper", fread($out, 1024));

        // shutdown the proc!
        proc_close($proc);

    }

}