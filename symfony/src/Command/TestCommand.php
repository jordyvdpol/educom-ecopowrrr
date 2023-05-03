<?php

namespace App\Command;

use App\Service\KlantenService;
use App\Service\DummyDataService;
use App\Service\PrijsService;
use App\utilities\PostcodeUtils;
use App\utilities\activeerDummyApparaatUtils;
use App\utilities\uitlezenDataUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'test',
    description: 'Test een functie uit met command line',
)]
class TestCommand extends Command{

    private EntityManagerInterface $entityManager;
    private DummyDataService $DummyDataService;
    private KlantenService $KlantenService;
    private PrijsService $PrijsService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService, KlantenService $KlantenService, PrijsService $PrijsService) {
        parent::__construct();
        $this -> entityManager = $entityManager;
        $this -> DummyDataService = $DummyDataService;
        $this -> KlantenService = $KlantenService;
        $this -> PrijsService = $PrijsService;
    }
    
    protected function configure(): void{
    }

    protected function execute(InputInterface $input, OutputInterface $output): int{
        $io = new SymfonyStyle($input, $output);
        $io->title('Test Functie');

        $data = $this -> DummyDataService -> calcJaarlijkseOmzet();
        // $data = DummyDataService::ophalenKlantData();
        dump ($data);
        $io->success('Success.');

        return Command::SUCCESS;
    }
}
