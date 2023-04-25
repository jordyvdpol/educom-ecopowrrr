<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;


#[AsCommand(
    name: 'app:artist:create',
    description: 'Maak een artiest',
)]
class CreateArtistCommand extends Command
{
    private $artistService;

    public function __construct(ArtiestService $artistService)
    {
        $this->artistService = $artistService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('naam', InputArgument::REQUIRED, 'Artiest naam')
            ->addArgument('genre', InputArgument::REQUIRED, 'Musiek genre')
            ->addArgument('omschrijving', InputArgument::OPTIONAL, 'Omschrijving van de artiest')
            ->addArgument('afbeeldingUrl', InputArgument::OPTIONAL, 'Url naar afbeelding')
            ->addArgument('website', InputArgument::OPTIONAL, 'url naar website')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $allArg = $input->getArguments();	

        foreach($allArg as $key => $value) {
            if($value != null) {
               $io->note(sprintf('You passed argument %s with value: %s',$key, $value));
            }
            else{
                $question = new Question('Please enter ' . $key);

                $allArg[$key] = $io->askQuestion($question);
                $io->note(sprintf('You passed argument %s with value: %s',$key, $allArg[$key]));
            }
        }

        $artiest =[
            "naam" => $allArg['naam'],
            "genre" => $allArg['genre'],
            "omschrijving" => $allArg['omschrijving'],
            "afbeeldingUrl" => $allArg['afbeeldingUrl'],
            "website" => $allArg['website']
        ];
       
   
        $opt1 = $input->getOption('option1');

        if ($opt1 == 1) {
            $io->note(sprintf('You passed an option: %s', $opt1));
        }

        $this->artistService->saveArtist($artiest);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}