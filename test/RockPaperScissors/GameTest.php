<?php

use RockPaperScissors\Game;

class GameTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider winningChoices
     * @test
     */
    public function playerWin($player1Choice, $player2Choice, $winner){

        $player1 = $this->mockPlayer($player1Choice);
        $player2 = $this->mockPlayer($player2Choice);

        $game = new Game();

        $game->play($player1, $player2);

        $expectedWinner = $winner==1?$player1:$player2;

        $this->assertEquals($expectedWinner, $game->winner());
    }

    /**
     * @dataProvider drawingChoices
     * @test
     */
    public function playersDraw($player1Choice, $player2Choice){

        $player1 = $this->getMock('RockPaperScissors\Player');
        $player2 = $this->getMock('RockPaperScissors\Player');

        $player1->expects($this->once())
                ->method('makeChoice');
        $player1->expects($this->once())
                ->method('revealHand')
                ->will($this->returnValue($player1Choice));

        $player2->expects($this->once())
                ->method('makeChoice');
        $player2->expects($this->once())
                ->method('revealHand')
                ->will($this->returnValue($player2Choice));

        $game = new Game();

        $game->play($player1, $player2);

        $this->assertTrue($game->isDraw());

    }

    /**
     * @test
     */
    public function invalidHand(){

        $this->setExpectedException('RockPaperScissors\Exception\InvalidHand');

        $game = new Game();

        $game->play($this->mockPlayer("SPOCK"), $this->mockPlayer(Game::ROCK));

    }

    /**
     * @test
     */
    public function printResult(){

        $expected =<<<EOL
James chose rock
Bob chose scissors
rock beats scissors
James won
EOL;



        $mockWriter = $this->getMock('RockPaperScissors\Writer');

        $mockWriter->expects($this->once())
                   ->method('write')
                   ->with($expected);

        // execute
        $game = new Game();
        $game->play($this->mockPlayer(Game::ROCK, "James"), $this->mockPlayer(Game::SCISSORS, "Bob"));
        $game->printResult($mockWriter);

        // assert
    }

    /**
     * @test
     */
    public function printResultThrowsExceptionIfGameNotPlayedYet(){

        $this->setExpectedException('RockPaperScissors\Exception\UnplayedGame');
        $game = new Game();
        $game->printResult($this->getMock('RockPaperScissors\Writer'));

    }

    /**
     * @test
     */
    public function replayMakesPlayersDecideAndRevealAgain(){

        $p1 = $this->getMock('RockPaperScissors\Player');
        $p2 = $this->getMock('RockPaperScissors\Player');
        $p1->expects($this->at(1))
           ->method('revealHand')
           ->will($this->returnValue(Game::PAPER));
        $p2->expects($this->at(1))
           ->method('revealHand')
           ->will($this->returnValue(Game::ROCK));

        $p1->expects($this->at(3))
           ->method('revealHand')
           ->will($this->returnValue(Game::PAPER));
        $p2->expects($this->at(3))
           ->method('revealHand')
           ->will($this->returnValue(Game::SCISSORS));

        $game = new Game();
        $game->play($p1, $p2);

        $this->assertTrue($game->hasWinner());
        $this->assertEquals($p1, $game->winner());

        $game->replay();

        // assert
        $this->assertTrue($game->hasWinner());
        // p2 wins the 2nd game so the last winner must be p2
        $this->assertEquals($p2, $game->winner());
    }

    /**
     * @test
     */
    public function replayThrowsExceptionIfPlayHasntOccured(){

        $this->setExpectedException('RockPaperScissors\Exception\UnplayedGame');
        $game = new Game();
        $game->replay();

    }


    /**
     * @helper
     * @param $hand
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function mockPlayer($hand, $name = null){
        $player = $this->getMock('RockPaperScissors\Player');
        $player->expects($this->atLeastOnce())
                ->method('makeChoice');
        $player->expects($this->atLeastOnce())
                ->method('revealHand')
                ->will($this->returnValue($hand));

        if(!empty($name)){
            $player->expects($this->any())
                   ->method('name')
                   ->will($this->returnValue($name));
        }
        return $player;
    }


    /**
     *
     */
    public function winningChoices(){
        return array(
            array(Game::ROCK, Game::SCISSORS, 1),
            array(Game::ROCK, Game::PAPER, 2),
            array(Game::SCISSORS, Game::ROCK, 2),
            array(Game::SCISSORS, Game::PAPER, 1),
            array(Game::PAPER, Game::ROCK, 1),
            array(Game::PAPER, Game::SCISSORS, 2),

        );

    }

    /**
     *
     */
    public function drawingChoices(){
        return array(
            array(Game::PAPER, Game::PAPER),
            array(Game::SCISSORS, Game::SCISSORS),
            array(Game::ROCK, Game::ROCK),

        );
    }


}