<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Klanten;

class klantController extends AbstractController
{
    #[Route('/registreerKlant', name: 'klant registratie')]
    public function index(): Response
    {
        $klant = [
            "voornaam" => "De Melkweg",
            "achternaam" => "Lijnbaansgracht 234a",
            "postcode" => "1017PH",
            "huisnummer" => "33",
        ];


       $rep = $this->getDoctrine()->getRepository(Klanten::class);
       $result = $rep->saveKlanten($klant);

       dd($result);

    }
}