<?php
namespace App\Command;

use App\Service\DummyDataService;
use App\utilities\maakDummyDataUtils;
use App\utilities\PostcodeUtils;
use App\utilities\activeerDummyApparaatUtils;
use App\utilities\uitlezenDataUtils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:updateKlant', 
    description: 'Update apparaat dummy data van klant in database',
)]
class updateKlant extends Command{
    private EntityManagerInterface $entityManager;
    private DummyDataService $DummyDataService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService) {
        parent::__construct();
        $this -> entityManager = $entityManager;
        $this -> DummyDataService = $DummyDataService;
    }

    protected function configure(): void{
        $this
            ->setDescription('Update dummy data in database')
            ->addArgument('klantId', InputArgument::OPTIONAL, 'klantId')
            ->addArgument('aantal', InputArgument::OPTIONAL, 'Aantal')
            ->addArgument('jaar', InputArgument::OPTIONAL, 'jaar')
            ->addArgument('maand', InputArgument::OPTIONAL, 'maand')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int{
        $io = new SymfonyStyle($input, $output);
        $io->title('Klant registratie');
        $klantId = $input->getArgument('klantId') ?: $io->ask('Klantnummer?');
        $aantal = $input->getArgument('aantal') ?: $io->ask('Aantal panelen?');
        $jaar = $input->getArgument('jaar') ?: $io->ask('Jaartal?');
        $maand = $input->getArgument('maand') ?: $io->ask('Maand (in getal)?');


        // Fetch dummy data apparaat
        $dummyData = maakDummyDataUtils::maakDummyData($klantId, $aantal, $jaar, $maand);

        // Dummy data ophalen en opslaan in database
        $result = $this -> DummyDataService->registreerDummyData($dummyData, $klantId);
        $io->section($result);

        return Command::SUCCESS;
    }
}