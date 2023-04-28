<?php

namespace App\Command;

use App\Entity\Klanten;
use App\Entity\DummyData;

use App\Repository\KlantenRepository;
use App\Repository\DummyDataRepository;

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
class RegistreerKlant extends Command
{
    private EntityManagerInterface $entityManager;
    private KlantenRepository $KlantenRepository;
    private DummyDataRepository $DummyDataRepository;


    public function __construct(EntityManagerInterface $entityManager, KlantenRepository $KlantenRepository, DummyDataRepository $DummyDataRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->KlantenRepository = $KlantenRepository;
        $this->DummyDataRepository = $DummyDataRepository;

    }

    protected function configure(): void
    {
        $this
            ->setDescription('Registreer nieuwe klant in database')
            ->addArgument('voornaam', InputArgument::OPTIONAL, 'Voornaam')
            ->addArgument('achternaam', InputArgument::OPTIONAL, 'Achternaam')
            ->addArgument('postcode', InputArgument::OPTIONAL, 'Postcode')
            ->addArgument('huisnummer', InputArgument::OPTIONAL, 'Huisnummer')
            ->addArgument('aantal', InputArgument::OPTIONAL, 'Aantal')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Klant registratie');
        $voornaam = $input->getArgument('voornaam') ?: $io->ask('Voornaam?');
        $achternaam = $input->getArgument('achternaam') ?: $io->ask('Achternaam?');
        $postcode = $input->getArgument('postcode') ?: $io->ask('Postcode?');
        $huisnummer = $input->getArgument('huisnummer') ?: $io->ask('Huisnummer?');
        $aantal = $input->getArgument('aantal') ?: $io->ask('Aantal panelen?');

        // Fetch postcode data
        $postcodeData = PostcodeUtils::fetchPostcodeData($postcode, $huisnummer);


        $klant = new Klanten();
        $klant->setVoornaam($voornaam);
        $klant->setAchternaam($achternaam);
        $klant->setPostcode($postcode);
        $klant->setHuisnummer($huisnummer);
        $klant->setStad($postcodeData['city']);
        $klant->setGemeente($postcodeData['municipality']);
        $klant->setProvincie($postcodeData['province']);

        try {
            $success = $this->KlantenRepository->save($klant, true);
            dump($success);
            if ($success) {
                $io->success(sprintf('Klant %s %s is geregistreerd', $voornaam, $achternaam));
                $klantId = $klant->getId();
                $io->writeln(sprintf('Klant ID: %d', $klantId));
            } else {
                $io->error('Klant is nog niet aan de database toegevoegd');
            }
        } catch (\Exception $e) {
            $io->error(sprintf('Er is iets misgegaan bij het registreren van de klant: %s', $e->getMessage()));
        }



        // Activeer apparaat
        activeerDummyApparaatUtils::activatiebericht('actief', $klantId, $aantal);

        // Fetch dummy data apparaat
        $dummyData = uitlezenDataUtils::uitlezenDummyData($klantId);
        $data = new DummyData();
        
        $klantRepository = $this -> entityManager -> getRepository(Klanten::class);
        $klantnummer = $klantRepository->find($klantId);


        $data -> setKlantnummer($klantnummer);
        $data -> setMessageId($dummyData['message_id']);
        $data -> setStatus($dummyData['status']);
        $data -> setDate($dummyData['date']);
        $data -> setJaar($dummyData['jaar']);
        $data -> setMaand($dummyData['maand']);
        $data -> setTotalYield($dummyData['total_yield']);
        $data -> setMonthYield($dummyData['month_yield']);
        $data -> setTotalSurplus($dummyData['total_surplus']);
        $data -> setMonthSurplus($dummyData['month_surplus']);

        try {
            $success = $this->DummyDataRepository->save($data, true);
            dump($success);
            if ($success) {
                $io->success(sprintf('Data van klant %s %s is succesvol opgehaald en opgeslagen', $voornaam, $achternaam));
            } else {
                $io->error('Data is niet aan de database toegevoegd');
            }
        } catch (\Exception $e) {
            $io->error(sprintf('Er is iets misgegaan bij het registreren van de klant: %s', $e->getMessage()));
        }
        

        return Command::SUCCESS;
    }

}
