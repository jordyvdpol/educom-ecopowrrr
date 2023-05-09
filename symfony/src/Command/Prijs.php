<?php
namespace App\Command;

use App\Service\PrijsService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:Prijs',
    description: 'Voeg prijs data toe in database'
)]

class Prijs extends Command{
    private EntityManagerInterface $entityManager;
    Private PrijsService $PrijsService;

    public function __construct(EntityManagerInterface $entityManager, PrijsService $PrijsService)
    {
        parent::__construct();
        $this -> entityManager = $entityManager;
        $this -> PrijsService = $PrijsService;
    }

    protected function configure(): void{
        $this
        ->setDescription ('Registreer prijs data in database')
        ->addArgument('jaar', InputArgument::OPTIONAL, 'jaar')
        ->addArgument('maand', InputArgument::OPTIONAL, 'maand')
        ->addArgument('inkoop_prijs_KwH', InputArgument::OPTIONAL, 'inkoop_prijs_KwH')
        ->addArgument('verkoop_prijs_KwH', InputArgument::OPTIONAL, 'verkoop_prijs_KwH')
        ;
    }

    protected function execute (Inputinterface $input, OutputInterface $output): int{
        $io = new SymfonyStyle($input, $output);
        $io->title('Prijs registratie');
        $jaar = $input->getArgument('jaar') ?: $io->ask('Jaar?');
        $maand = $input->getArgument('maand') ?: $io->ask('Maand?');
        $inkoop_prijs_KwH = $input->getArgument('inkoop_prijs_KwH') ?: $io->ask('Inkoop prijs KwH (in cent)?');
        $verkoop_prijs_KwH = $input->getArgument('verkoop_prijs_KwH') ?: $io->ask('Verkoop prijs KwH (in cent)?');

        $prijsData = [
            'jaar' => $jaar,
            'maand' => $maand,
            'inkoop_prijs_KwH' => $inkoop_prijs_KwH,
            'verkoop_prijs_KwH' => $verkoop_prijs_KwH
        ];
        
        $result = $this -> PrijsService -> registreerPrijs($prijsData);
        $io->section($result);

        return Command::SUCCESS;
    }
}

?>