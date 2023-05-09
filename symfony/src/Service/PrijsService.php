<?php
namespace App\Service;

use App\Entity\Prijs;
use App\Repository\PrijsRepository;
use Doctrine\ORM\EntityManagerInterface;

class PrijsService {
    private $PrijsRepository;

    public function __construct(EntityManagerInterface $entityManager, PrijsRepository $PrijsRepository){
        $this->entityManager = $entityManager;
        $this->PrijsRepository = $PrijsRepository;
    }

    public function registreerPrijs($prijsData) {
        $jaar = $prijsData['jaar'];
        $maand = $prijsData['maand'];
        $existingPrijs = $this->PrijsRepository->findOneBy(['jaar' => $jaar, 'maand' => $maand]);
        if (!$existingPrijs) {
            $prijs = new Prijs();
            $prijs->setJaar($jaar);
            $prijs->setMaand($maand);
        } else {
            $prijs = $existingPrijs;
        }
        $prijs->setInkoopPrijsKwH($prijsData['inkoop_prijs_KwH']);
        $prijs->setVerkoopPrijsKwH($prijsData['verkoop_prijs_KwH']);
        try {
            $success = $this->PrijsRepository->save($prijs, true);
            if ($success) {
               return ('Prijs data is succesvol toegevoegd aan de database');
            } else {
                error('Prijs data is nog niet aan de database toegevoegd');
            }
        } catch (\Exception $e) {
            return (sprintf('Er is iets misgegaan bij het registreren van de prijs data: %s', $e->getMessage()));
        }
    }

    public function getAllPrijsData() {
        $prijsData = $this->PrijsRepository->findAll();
        $prijsDataArray = [];
        foreach ($prijsData as $data) {
            $dataArray = [
                'id' => $data->getId(),
                'jaar' => $data->getJaar(),
                'maand' => $data->getMaand(),
                'inkoopPrijsKwH' => $data->getInkoopPrijsKwH(),
                'verkoopPrijsKwH' => $data->getVerkoopPrijsKwH()
            ];
            $prijsDataArray[] = $dataArray;
        }
        return $prijsDataArray;
    }
}

?>