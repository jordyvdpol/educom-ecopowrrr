<?php

namespace App\Service;

use App\Entity\DummyData;
use App\Repository\DummyDataRepository;
use App\Entity\Klanten;
use App\Repository\KlantenRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

use Doctrine\ORM\EntityManagerInterface;

class DummyDataService  {
    private $KlantenRepository;
    private $DummyDataRepository;
    private ClassMetadata $metadata;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, DummyDataRepository $DummyDataRepository, KlantenRepository $KlantenRepository)
    {
        $this->entityManager = $entityManager;
        $this->DummyDataRepository = $DummyDataRepository;
        $this->KlantenRepository = $KlantenRepository;
        $this->metadata = $this->entityManager->getClassMetadata(DummyData::class);

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


    public function ophalenKlantData () {
        $DummyDataRepository = $this -> entityManager -> getRepository(DummyData::class);
        $data = $DummyDataRepository->findAll();
        return $data;
    }


    public function loopData($data) {
        $result = [];
    
        foreach ($this->metadata->fieldMappings as $key => $mapping) {
            $type = $mapping['type'];
            $func = 'get' . ucwords(str_replace('_', '', $key));
    
            if (method_exists($data, $func)) {
                $value = $data->$func();
                $result[$key] = $value;
            }
        }
        return $result;      
    }
    

    public function getAllDummyData() {
        $data = $this->DummyDataRepository->findAllById();
        $id =[];
        $result = [];
        foreach ($data as $key){
            array_push($id, $key['id']);
            dump($key['id']);
            $data = $this -> DummyDataRepository->find($key['id']);
            if (!$data) {
                $result[] = 'no data available';
            }else {
                $dummyData = DummyDataService::loopData($data);
                $result[] = $dummyData;
            }
        }
        return $result;
    }
}


?>