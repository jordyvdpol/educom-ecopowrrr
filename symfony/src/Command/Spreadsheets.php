<?php

namespace App\Command;

use App\utilities\spreadsheet1Utils;
use App\utilities\spreadsheet2Utils;
use App\utilities\spreadsheet3Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;



#[AsCommand(
    name: 'app:Spreadsheets',
    description: 'Genereer spreadsheets'
)]
class Spreadsheets extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        spreadsheet1Utils $spreadsheet1Utils,
        spreadsheet2Utils $spreadsheet2Utils,
        spreadsheet3Utils $spreadsheet3Utils


    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->spreadsheet1Utils = $spreadsheet1Utils;
        $this->spreadsheet2Utils = $spreadsheet2Utils;
        $this->spreadsheet3Utils = $spreadsheet3Utils;
    }

    protected function configure()
    {
        $this->setName('generate:spreadsheet')
             ->setDescription('Generate a spreadsheet')
             ->addArgument('spreadsheet', InputArgument::OPTIONAL, 'spreadsheet')
             ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
{
    $io = new SymfonyStyle($input, $output);
    $io->title('Spreadsheet generator');
    $spreadsheet = $input->getArgument('spreadsheet') ?: $io->ask('welke spreadsheet wil je? opties: 1, 2, of 3');
    
    if ($spreadsheet === '1') {
        $response = $this->spreadsheet1Utils->maakSpreadsheet();
    } elseif ($spreadsheet === '2') {
        $response = $this->spreadsheet2Utils->maakSpreadsheet();
    } elseif ($spreadsheet === '3') {
        $response = $this->spreadsheet3Utils->maakSpreadsheet();
    } else {
        $io->error('Ongeldige optie geselecteerd');
        return Command::FAILURE;
    }

    $io->section('');
    $io->section('Success?');


    return Command::SUCCESS;
}

}


?>