<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Repository\klantRepository;


#[AsCommand(
    name: 'app:registreerKlant',
    description: 'Registreer nieuwe klant in database',
)]
class registreerKlant extends Command{

    // private $klantenRepository;

    // public function __construct(klantenRepository $klantenRepository)
    // {
    //     parent::__construct();

    //     $this->klantenRepository = $klantenRepository;
    // }




    protected function configure()
    {
        $this
            ->setHelp('Met deze command kun je nieuwe klanten registreren in de database')
            ->addArgument('voornaam', InputArgument::OPTIONAL, 'naam')
            ->addArgument('achternaam', InputArgument::OPTIONAL, 'naam')
            ->addArgument('postcode', inputArgument::OPTIONAL, 'postcode')
            ->addArgument('huisnummer', inputArgument::OPTIONAL, 'huisnummer')
            // ->addArgument('status', InputArgument::OPTIONAL, 'status')
        ;   
    }
    
    protected function execute(InputInterface $input,
                               OutputInterface $output,)
    {
        $io = new SymfonyStyle($input, $output);
        $io -> title('klant registratie');

        $voornaam = $input->getArgument('voornaam') ?: $io->ask('voornaam?');
        $achternaam = $input->getArgument('achternaam') ?: $io->ask('achternaam?');
        $postcode = $input->getArgument('postcode') ?: $io->ask('postcode?');	
        $huisnummer = $input->getArgument('huisnummer') ?: $io->ask('huisnummer?');
      
            // $this->klantRepository->savePodium($klant);
            return Command::SUCCESS;
        }        
} 

