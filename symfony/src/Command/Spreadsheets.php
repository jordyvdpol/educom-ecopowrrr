<?php

namespace App\Command;

use App\Service\DummyDataService;
use App\utilities\spreadsheetUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:Spreadsheets',
    description: 'Genereer spreadsheets'
)]
class Spreadsheets extends Command
{
    private EntityManagerInterface $entityManager;
    private DummyDataService $DummyDataService;
    private spreadsheetUtils $spreadsheetUtils;

    public function __construct(
        EntityManagerInterface $entityManager,
        DummyDataService $DummyDataService,
        spreadsheetUtils $spreadsheetUtils
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->DummyDataService = $DummyDataService;
        $this->spreadsheetUtils = $spreadsheetUtils;
    }

    protected function configure()
    {
        $this->setName('generate:spreadsheet')
             ->setDescription('Generate a spreadsheet');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Spreadsheet generator');
        $response = $this->spreadsheetUtils->maakSpreadsheet();
        $io->section('');
        $io->section('Success?');


        return Command::SUCCESS;
    }
}


?>