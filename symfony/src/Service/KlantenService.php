<?php

namespace App\Service;

use App\Entity\Klanten;
use App\Repository\KlantenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;


class KlantenService {
    private $KlantenRepository;
    
    public function __construct(EntityManagerInterface $entityManager, KlantenRepository $KlantenRepository)
    {
        $this->entityManager = $entityManager;
        $this->KlantenRepository = $KlantenRepository;
        $this->metadata = $this->entityManager->getClassMetadata(Klanten::class);
    }

    public function getKlant($klantId) {
        try {
            $klantnummer = $this -> KlantenRepository ->find($klantId);
            return $klantnummer;
        } catch (\Exception $e) {
            return (sprintf('Er is iets misgegaan bij het ophalen van de klant: %s', $e->getMessage()));
        }
    }

    public function registreerKlanten($klantData) {
        $klant = new Klanten();

        $klant->setVoornaam($klantData['voornaam']);
        $klant->setAchternaam($klantData['achternaam']);
        $klant->setPostcode($klantData['postcode']);
        $klant->setHuisnummer($klantData['huisnummer']);
        $klant->setStad($klantData['stad']);
        $klant->setGemeente($klantData['gemeente']);
        $klant->setProvincie($klantData['provincie']);

        try {
            $success = $this->KlantenRepository->save($klant, true);
            if ($success) {
                $klantId = $klant->getId();
               return $klantId;
            } else {
                error('Klant is nog niet aan de database toegevoegd');
            }
        } catch (\Exception $e) {
            return (sprintf('Er is iets misgegaan bij het registreren van de klant: %s', $e->getMessage()));
        }
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
    

    public function getAllKlantenData() {
        $data = $this->KlantenRepository->findAllById();
        $id =[];
        $result = [];
        foreach ($data as $key){
            array_push($id, $key['id']);
            $data = $this -> KlantenRepository->find($key['id']);
            if (!$data) {
                $result[] = 'no data available';
            }else {
                $klantData = KlantenService::loopData($data);
                $result[] = $klantData;
            }
        }
        return $result;
    }


    
}


?>