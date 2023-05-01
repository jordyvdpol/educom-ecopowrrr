<?php

namespace App\Service;

use App\Entity\Klanten;
use App\Repository\KlantenRepository;
use Doctrine\ORM\EntityManagerInterface;


class KlantenService {
    private $KlantenRepository;
    

    public function __construct(EntityManagerInterface $entityManager, KlantenRepository $KlantenRepository)
    {
        $this->entityManager = $entityManager;
        $this->KlantenRepository = $KlantenRepository;
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
}


?>