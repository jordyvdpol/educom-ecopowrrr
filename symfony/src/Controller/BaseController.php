<?php 

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    private $logger;
    
    protected function log($msg) {
        $this->logger->info($msg);   
    }
    
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    } 
}