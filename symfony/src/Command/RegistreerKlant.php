<?php

namespace App\Command;

use App\Entity\Klanten;
use App\Repository\KlantenRepository;
use App\utilities\PostcodeUtils;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
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

    public function __construct(EntityManagerInterface $entityManager, KlantenRepository $KlantenRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->KlantenRepository = $KlantenRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Registreer nieuwe klant in database')
            ->addArgument('klantnummer', InputArgument::OPTIONAL, 'Klantnummer')
            ->addArgument('voornaam', InputArgument::OPTIONAL, 'Voornaam')
            ->addArgument('achternaam', InputArgument::OPTIONAL, 'Achternaam')
            ->addArgument('postcode', InputArgument::OPTIONAL, 'Postcode')
            ->addArgument('huisnummer', InputArgument::OPTIONAL, 'Huisnummer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Klant registratie');

        $klantnummer = $input->getArgument('klantnummer') ?: $io->ask('Klantnummer?');
        $voornaam = $input->getArgument('voornaam') ?: $io->ask('Voornaam?');
        $achternaam = $input->getArgument('achternaam') ?: $io->ask('Achternaam?');
        $postcode = $input->getArgument('postcode') ?: $io->ask('Postcode?');
        $huisnummer = $input->getArgument('huisnummer') ?: $io->ask('Huisnummer?');

        // Fetch postcode data
        $postcodeData = PostcodeUtils::fetchPostcodeData($postcode, $huisnummer);


        $klant = new Klanten();
        $klant->setKlantnummer($klantnummer);
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
            } else {
                $io->error('Klant is nog niet aan de database toegevoegd');
            }
        } catch (\Exception $e) {
            $io->error(sprintf('Er is iets misgegaan bij het registreren van de klant: %s', $e->getMessage()));
        }

        // Fetch dummy data apparaat
        $dummyData = dummyDataUtils::uitlezenDummyData($klantnummer);
        
        

        return Command::SUCCESS;
    }

}
