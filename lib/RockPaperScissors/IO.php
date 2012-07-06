<?php

namespace RockPaperScissors;

class IO implements Writer, Reader {

    public function __construct(){
        defined("STDIN") || define("STDIN", fopen("php://stdin", "r"));
        defined("STDOUT") || define("STDOUT", fopen("php://stdout", "w"));
    }

    public function write($what){
        // Format it a little nicer!
        $what = trim($what) . "\n";
        fwrite(STDOUT, $what);
    }

    public function read(){
        // i lose interest after 1024 bytes, sorry
        return trim(fread(STDIN, 1024));
    }

}