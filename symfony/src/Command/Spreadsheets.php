<?php
namespace App\Command;

use App\Service\DummyDataService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:Spreadsheets',
    description: 'Genereer spreadsheets'
)]

class Spreadhseets extends Command{
    private EntityManagerInterface $entityManager;
    Private DummyDataService $DummyDataService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService)
    {
        parent::__construct();
        $this -> entityManager = $entityManager;
        $this -> DummyDataService = $DummyDataService;
    }

    protected function configure(): void{
        $this
        ->setDescription ('Registreer prijs data in database')
        ->addArgument('jaar', InputArgument::OPTIONAL, 'jaar')
        ;
    }

    protected function execute (Inputinterface $input, OutputInterface $output): int{
        $io = new SymfonyStyle($input, $output);
        $io->title('Prijs registratie');
        $jaar = $input->getArgument('jaar') ?: $io->ask('Jaar?');


        return Command::SUCCESS;
    }

}

?>