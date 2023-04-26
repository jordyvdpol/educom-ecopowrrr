<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Klanten;

class klantController extends AbstractController
{
    #[Route('/registreerKlant', name: 'klant registratie')]
    public function index(entityManagerInterface $entityManager): Response
    {
        $klant = [
            "klantnummer" => "5415616",
            "voornaam" => "De Melkweg",
            "achternaam" => "Lijnbaansgracht 234a",
            "postcode" => "1017PH",
            "huisnummer" => "33",
        ];

        
        $repository = $entityManager -> getRepository(Klanten::class);
        $result = $repository ->  saveKlanten($klant);
        // $entityManager -> persist($klant);

        // $entityManage->flush();


        return new Response('succes');

    }
}