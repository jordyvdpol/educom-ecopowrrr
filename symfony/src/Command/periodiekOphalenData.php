<?php

namespace App\Command;

use App\Service\KlantenService;
use App\Service\DummyDataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
// use Symfony\Component\Scheduler;

#[AsCommand(
    name: 'app:periodiekOphalenData',
    description: 'Dit commando haalt periodiek de data op voor alle klanten'
)]

class periodiekOphalenData extends Command
{
    protected static $defaultName = 'periodiek:ophalen:data';

    private DummyDataService $dummyDataService;
    private KlantenService $klantenService;

    public function __construct(DummyDataService $dummyDataService, KlantenService $klantenService)
    {
        $this->dummyDataService = $dummyDataService;
        $this->klantenService = $klantenService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Periodiek data ophalen voor alle klanten')
            ->setHelp('Dit commando haalt periodiek de data op voor alle klanten.');
            // ->addOption('interval', 'i', InputOption::VALUE_REQUIRED, 'the interval in seconds', 60);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $interval = $input->getOption('interval');
        // $scheduler = new Scheduler();
        // $scheduler->schedule(new \DateInterval('PT' . $interval . 'S'), 'your:command', ['--option' => 'value']);
        // $scheduler->run();

        $klantData = $this->klantenService->getAllKlantenData();
        $jaar = date('Y');
        $maand = ltrim(date('m'), '0');
        foreach ($klantData as $klant) {
            $command = [
                'php', 'bin/console',
                'app:updateKlant',
                $klant['id'],
                '3',
                $jaar,
                $maand
            ];
            $process = new Process($command);
            try {
                $process->mustRun();
                $output->writeLn($process->getOutput());
            } catch (ProcessFailedException $exception) {
                $output->writeLn($exception->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
