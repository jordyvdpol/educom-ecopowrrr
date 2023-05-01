<?php

namespace App\Service;

use App\Entity\DummyData;
use App\Repository\DummyDataRepository;
use App\Entity\Klanten;
use App\Repository\KlantenRepository;

use Doctrine\ORM\EntityManagerInterface;

class DummyDataService  {
    private $KlantenRepository;
    private $DummyDataRepository;

    public function __construct(EntityManagerInterface $entityManager, DummyDataRepository $DummyDataRepository, KlantenRepository $KlantenRepository)
    {
        $this->entityManager = $entityManager;
        $this->DummyDataRepository = $DummyDataRepository;
        $this->KlantenRepository = $KlantenRepository;
    }

    public function registreerDummyData ($dummyData, $klantId) {
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
            if ($success) {
                return (sprintf('Data van klant met id %s is succesvol opgehaald en opgeslagen', $klantId));
            } else {
                error('Data is nog niet aan de database toegevoegd');
            }
        } catch (\Exception $e) {
            return (sprintf('Er is iets misgegaan bij het registreren van de klant: %s', $e->getMessage()));
        }
    }



}

?>