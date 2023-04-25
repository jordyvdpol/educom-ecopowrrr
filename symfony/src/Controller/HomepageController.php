<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/')]
class HomepageController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    #[Template()]
    public function index()
    {
        return ['controller_name' => 'HomepageController'];
    }

     
    #[Route('/backhome', name: 'backhome')]    
    public function backhome() {
        return $this->redirectToRoute('homepage');
    }


    #[Route('/data.{_format}', name: 'api_output', requirements: ['_format' => 'xml|json'])]
    public function api($_format) {
          $data = [
              ["id" => 1, "naam" => "Piet"],
              ["id" => 2, "naam" => "Wilma"],
              ["id" => 3, "naam" => "Harrie"]
          ];
          if($_format == "json") {
              return($this->json($data));
          } else {
          
          /// Om een array naar XML om te zetten is een parser nodig.
          /// Hier even een very quick en very dirty oplossing
          /// - die je eventueel ook met Twig zou kunnen maken.
          $d = "<data>";
              foreach($data as $record) {
                      $id = $record["id"];
                      $naam = $record["naam"];
                      $d .= "<record id='$id'>$naam</record>";
              }   
              $d .= "</data>";
              return(new Response($d));
          }
    }

    #[Route('/data', name:'homepage_save_data')] 
    public function getData(){
        $data = file_get_contents('http://127.0.0.1:3000/haalDummyData');
        dd($data);
    }

}