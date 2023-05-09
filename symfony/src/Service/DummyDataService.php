<?php

namespace App\Service;

use App\Entity\DummyData;
use App\Repository\DummyDataRepository;
use App\Entity\Klanten;
use App\Repository\KlantenRepository;
use App\Service\KlantenService;
use App\Service\PrijsService;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;

class DummyDataService  {
    private $KlantenRepository;
    private $DummyDataRepository;
    private ClassMetadata $metadata;
    private EntityManagerInterface $entityManager;
    private $PrijsService;
    private $KlantenService;

    public function __construct(EntityManagerInterface $entityManager, DummyDataRepository $DummyDataRepository, KlantenRepository $KlantenRepository, PrijsService $PrijsService, KlantenService $KlantenService)
    {
        $this->entityManager = $entityManager;
        $this->DummyDataRepository = $DummyDataRepository;
        $this->KlantenRepository = $KlantenRepository;
        $this->metadata = $this->entityManager->getClassMetadata(DummyData::class);
        $this->PrijsService = $PrijsService;
        $this->KlantenService = $KlantenService;
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

    public function calcMaandelijkseOmzetKlant($data = null){
        if ($data === null) {
            $allDummyData = DummyDataService::getAllDummyData();
        } else {
            $allDummyData = $data;
        }
        $allPrijsData = $this->PrijsService->getAllPrijsData();
        $allKlantenData = $this-> KlantenService -> getAllKlantenData();

        $result = [];
        foreach ($allDummyData as $dummy) {
            $klantnummer = $dummy['klantnummer'];
            $jaar = $dummy['jaar'];
            $maand = $dummy['maand'];
            $month_surplus = $dummy['month_surplus'];
            $verkoopPrijs = null;
            $klant = $this -> KlantenRepository -> findOneBy(['id' => $klantnummer]);
            $gemeente = $klant -> getGemeente();
            foreach ($allPrijsData as $maandPrijsData) {
                if ($maandPrijsData['jaar'] == $jaar && $maandPrijsData['maand'] == $maand) {
                    $verkoopPrijs = $maandPrijsData['verkoopPrijsKwH'];
                    $inkoopPrijs = $maandPrijsData['inkoopPrijsKwH'];
                    break;
                }
            }
            if ($verkoopPrijs !== null) {
                $omzet = $month_surplus * $verkoopPrijs;
                $winst = $omzet - ($month_surplus * $inkoopPrijs);
                    $result[$klantnummer][$jaar][$maand]['omzet'] = $omzet;
                    $result[$klantnummer][$jaar][$maand]['winst'] = $winst;
                    $result[$klantnummer][$jaar][$maand]['KwH'] = $month_surplus;
                    $result[$klantnummer][$jaar][$maand]['gemeente'] = $gemeente;
            }
        }
        return $result;
    }

    public function calcJaarlijkseOmzetKlant(){
        $maandelijkseOmzet = DummyDataService::calcMaandelijkseOmzetKlant();
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
        return $result;
    }

    public function calcJaarlijkseGetallenGemeente(){
        $maandelijkseOmzet = DummyDataService::calcMaandelijkseOmzetKlant();
        $result = [];
        $omzetTotal = 0;
        $KwHTotal = 0;

        foreach ($maandelijkseOmzet as $klantnummer => $jaar) {
            foreach ($jaar as $jaartal => $maanden) {
                foreach ($maanden as $maand => $data) {
                    $gemeente = $data['gemeente'];
                    if (!isset($result[$gemeente])) {
                        $result[$gemeente] = [
                            'omzet' => 0,
                            'winst' => 0,
                            'KwH' => 0,
                        ];
                    }
                    $result[$gemeente]['omzet'] += $data['omzet'];
                    $result[$gemeente]['winst'] += $data['winst'];
                    $result[$gemeente]['KwH'] += $data['KwH'];
                }
            }
        }
        return $result;
    }

    public function calcMaandelijkseOmzet(){
        $huidigJaar = date('Y');
        $maandelijkseOmzetKlant = DummyDataService::calcMaandelijkseOmzetKlant();
        $result = [];
        foreach($maandelijkseOmzetKlant as $klant){
            foreach($klant as $jaarId => $jaar ){
                foreach($jaar as $maandId => $maand){
                    if(!isset($result[$jaarId][$maandId])){
                        $result[$jaarId][$maandId] = [
                            'omzet' => 0,
                            'winst' => 0,
                            'KwH' => 0
                        ];
                    }
                    $result[$jaarId][$maandId]['omzet'] += $maand['omzet'];
                    $result[$jaarId][$maandId]['winst'] += $maand['winst'];
                    $result[$jaarId][$maandId]['KwH'] += $maand['KwH'];
                }
            }
        }
        return $result;
    }

    public function calcJaarlijkseOmzet(){
        $huidigJaar = date('Y');
        $maandelijkseOmzet = DummyDataService::calcMaandelijkseOmzet();
        $result = [];
        foreach($maandelijkseOmzet as $jaarId => $jaar){
            foreach($jaar as $maand){
                if(!isset($result[$jaarId])){
                    $result[$jaarId] = [
                        'omzet' => 0,
                        'winst' => 0,
                        'KwH' => 0
                    ];
                $result[$jaarId]['omzet'] += $maand['omzet'];
                $result[$jaarId]['winst'] += $maand['winst'];
                $result[$jaarId]['KwH'] += $maand['KwH'];
                }
            }
        }
        return $result;
    }
    
    

}


?>


