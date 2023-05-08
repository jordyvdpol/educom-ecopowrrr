<?php
namespace App\Command;

use App\Service\KlantenService;
use App\Service\DummyDataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class periodiekOphalenData{
    private DummyDataService $DummyDataService;
    private KlantenService $klantenService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService, KlantenService $klantenService) {
        $this -> entityManager = $entityManager;
        $this -> DummyDataService = $DummyDataService;
        $this -> KlantenService = $klantenService;
    }
    public function periodiekOphalenData(){
        $klantData = $this -> KlantenService-> getAllKlantenData();
        // $date = new Date();
        $jaar = date('Y');
        $maand = date('m');

        foreach($klantData as $klant){
            $command = ['php', 'bin/console', 
                        'app:updateKlant', 
                        $klant['id'], 
                        '3',
                        $jaar, 
                        $maand
                    ];
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
}


?>