<?php

namespace App\Service;

use App\Entity\DummyData;
use App\Repository\DummyDataRepository;
use App\Entity\Klanten;
use App\Repository\KlantenRepository;
use App\Service\PrijsService;
use Doctrine\ORM\Mapping\ClassMetadata;

use Doctrine\ORM\EntityManagerInterface;

class DummyDataService  {
    private $KlantenRepository;
    private $DummyDataRepository;
    private ClassMetadata $metadata;
    private EntityManagerInterface $entityManager;
    private $PrijsService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataRepository $DummyDataRepository, KlantenRepository $KlantenRepository, PrijsService $PrijsService)
    {
        $this->entityManager = $entityManager;
        $this->DummyDataRepository = $DummyDataRepository;
        $this->KlantenRepository = $KlantenRepository;
        $this->metadata = $this->entityManager->getClassMetadata(DummyData::class);
        $this->PrijsService = $PrijsService;

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
            dd($this->metadata->fieldMappings);

            $type = $mapping['type'];
            $func = 'get' . ucwords(str_replace('_', '', $key));
    
            if (method_exists($data, $func)) {
                $value = $data->$func();

    
                // related entity
                if ($type === 'entity' && $value !== null) {
                    $value = $value->getId();
                    dd($value);
                }
                
    
                $result[$key] = $value;
            }
        }
    
        return $result;
    }
    
    

    public function getAllDummyData_Rene() {
        $data = $this->DummyDataRepository->findAllById();
        $id =[];
        $result = [];
        foreach ($data as $key){
            array_push($id, $key['id']);
            dump($key['id']);
            $data = $this -> DummyDataRepository->find($key['id']);
            // dd($data);
            if (!$data) {
                $result[] = 'no data available';
            }else {
                $dummyData = DummyDataService::loopData($data);
                $result[] = $dummyData;
            }
        }
        return $result;
    }

    public function getAllDummyData() {
        $dummyData = $this->DummyDataRepository->findAll();
        $dummyDataArray = [];
        foreach ($dummyData as $data) {
            $klanten = $data->getKlantnummer();
            $klantnummer = $klanten->getId();
            $dataArray = [
                'id' => $data->getId(),
                'message_id' => $data->getMessageId(),
                'klantnummer' => $klantnummer,
                'status' => $data->getStatus(),
                'date' => $data->getDate(),
                'jaar' => $data->getJaar(),
                'maand' => $data->getMaand(),
                'total_yield' => $data->getTotalYield(),
                'month_yield' => $data->getMonthYield(),
                'total_surplus' => $data->getTotalSurplus(),
                'month_surplus' => $data->getMonthSurplus(),
            ];
            $dummyDataArray[] = $dataArray;
        }
        return $dummyDataArray;
    }
    
    


    public function calcMaandelijkseOmzet(){
        $allDummyData = DummyDataService::getAllDummyData();
        $allPrijsData = $this->PrijsService->getAllPrijsData();
        $result = [];
        foreach ($allDummyData as $dummy) {
            $klantnummer = $dummy['klantnummer'];
            $jaar = $dummy['jaar'];
            $maand = $dummy['maand'];
            $month_surplus = $dummy['month_surplus'];
            $prijs = null;
            foreach ($allPrijsData as $maandPrijsData) {
                if ($maandPrijsData['jaar'] == $jaar && $maandPrijsData['maand'] == $maand) {
                    $prijs = $maandPrijsData['verkoopPrijsKwH'];
                    break;
                }
            }
            if ($prijs !== null) {
                $total = $month_surplus * $prijs;
                    $result[$klantnummer][$jaar][$maand]['omzet'] = $total;
                    $result[$klantnummer][$jaar][$maand]['KwH'] = $month_surplus;
            }
        }
        return $result;
    }

    public function calcJaarlijkseOmzet(){
        $maandelijkseOmzet = DummyDataService::calcMaandelijkseOmzet();
        $result = [];
        $omzetTotal = 0;
        $KwHTotal = 0;
        foreach($maandelijkseOmzet as $klantId => $klant) {
            foreach($klant as $jaarId => $jaar) {
                foreach($jaar as $maand){
                    $omzetTotal += $maand['omzet'];
                    $KwHTotal += $maand['KwH'];
                }
                $result[$klantId][$jaarId]['jaar'] = $jaarId;
                $result[$klantId][$jaarId]['omzet'] = $omzetTotal;
                $result[$klantId][$jaarId]['KwH'] = $KwHTotal;
                $omzetTotal = 0;
                $KwHTotal = 0 ;
            }
        }
        // dd($result);
        return $result;
    }
    
    

}


?>


