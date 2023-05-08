<?php

namespace App\Command;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class executeCommand{
    function execute(){
        // dd('test');
        $command = ['php', 'bin/console', 'app:updateKlant', '2', '3', '2023', '7'];
        $process = new Process($command);
        
        try {
            $process->mustRun();
        
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }

        echo $process->getOutput();
    }

}



?>