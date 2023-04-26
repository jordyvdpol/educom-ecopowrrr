<?php

namespace App\Command;

use App\Entity\Klanten;
use App\Repository\KlantRepository;
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
    name: 'app:registreer-klant',
    description: 'Registreer nieuwe klant in database',
)]
class RegistreerKlantCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private KlantRepository $klantRepository;

    public function __construct(EntityManagerInterface $entityManager, KlantRepository $klantRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->klantRepository = $klantRepository;
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
        $data = $this->fetchPostcodeData($postcode, $huisnummer);

        $klant = new Klanten();
        $klant->setKlantnummer($klantnummer);
        $klant->setVoornaam($voornaam);
        $klant->setAchternaam($achternaam);
        $klant->setPostcode($postcode);
        $klant->setHuisnummer($huisnummer);
        $klant->setStad($data['city']);
        $klant->setGemeente($data['municipality']);
        $klant->setProvincie($data['province']);

        $this->klantRepository->save($klant);

        $io->success(sprintf('Klant %s %s is geregistreerd', $voornaam, $achternaam));

        return Command::SUCCESS;
    }

    private function fetchPostcodeData(string $postcode, $huisnummer){
        $url = 'https://postcode.tech/api/v1/postcode/full?postcode=' . urlencode($postcode) . '&number=' . urlencode($huisnummer);
        $bearerToken = 'e1f29cae-b9b8-4ddd-b3dd-0fd976394914';

        $headers = [
            'Authorization: Bearer ' . $bearerToken
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Error fetching data: ' . curl_error($ch));
        }
        $data = json_decode($response, true);
        curl_close($ch);
        
        return $data;
    }
}
