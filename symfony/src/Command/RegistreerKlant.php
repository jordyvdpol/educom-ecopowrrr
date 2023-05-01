<?php
namespace App\Command;

use App\Service\KlantenService;
use App\Service\DummyDataService;
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
    name: 'app:RegistreerKlant',
    description: 'Registreer nieuwe klant in database',
)]
class RegistreerKlant extends Command{
    private EntityManagerInterface $entityManager;
    private DummyDataService $DummyDataService;
    private KlantenService $KlantenService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService, KlantenService $KlantenService) {
        parent::__construct();
        $this -> entityManager = $entityManager;
        $this -> DummyDataService = $DummyDataService;
        $this -> KlantenService = $KlantenService;
    }

    protected function configure(): void{
        $this
            ->setDescription('Registreer nieuwe klant in database')
            ->addArgument('voornaam', InputArgument::OPTIONAL, 'Voornaam')
            ->addArgument('achternaam', InputArgument::OPTIONAL, 'Achternaam')
            ->addArgument('postcode', InputArgument::OPTIONAL, 'Postcode')
            ->addArgument('huisnummer', InputArgument::OPTIONAL, 'Huisnummer')
            ->addArgument('aantal', InputArgument::OPTIONAL, 'Aantal')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int{
        $io = new SymfonyStyle($input, $output);
        $io->title('Klant registratie');
        $voornaam = $input->getArgument('voornaam') ?: $io->ask('Voornaam?');
        $achternaam = $input->getArgument('achternaam') ?: $io->ask('Achternaam?');
        $postcode = $input->getArgument('postcode') ?: $io->ask('Postcode?');
        $huisnummer = $input->getArgument('huisnummer') ?: $io->ask('Huisnummer?');
        $aantal = $input->getArgument('aantal') ?: $io->ask('Aantal panelen?');

        // Fetch postcode data
        $postcodeData = PostcodeUtils::fetchPostcodeData($postcode, $huisnummer);
        
        // Voeg klant aan database toe
        $klantData = [
            'voornaam' => $voornaam,
            'achternaam' => $achternaam,
            'postcode' => $postcode,
            'huisnummer' => $huisnummer,
            'stad' => $postcodeData['city'],
            'gemeente' => $postcodeData['municipality'],
            'provincie' => $postcodeData['province']
        ];

        $klantId = $this-> KlantenService->registreerKlanten($klantData);
        $io->section(sprintf('Succes: klant met ID %d is toegevoegd aan de database', $klantId));
        
        // Activeer apparaat
        activeerDummyApparaatUtils::activatiebericht('actief', $klantId, $aantal);

        // Fetch dummy data apparaat
        $dummyData = uitlezenDataUtils::uitlezenDummyData($klantId);

        // Dummy data ophalen en opslaan in database
        $result = $this -> DummyDataService->registreerDummyData($dummyData, $klantId);
        $io->section($result);

        return Command::SUCCESS;
    }
}