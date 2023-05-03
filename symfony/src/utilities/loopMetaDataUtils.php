<?php
namespace App\utilities;


use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DummyData;
use App\Entity\Klanten;


class loopMetaData{
    private ClassMetadata $metadata;
    private EntityManagerInterface $entityManager;
    private string $entityClassName;

    public function __construct(EntityManagerInterface $entityManager, string $entityClassName)
    {
        $this->entityManager = $entityManager;
        $this->entityClassName = $entityClassName;
        $this->metadata = $this->entityManager->getClassMetadata($this->entityClassName);
    }

    public  function loopData($data) {
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
}

// $entityManager = // create EntityManagerInterface instance
// $entityClassName = 'Klanten'; // or any other entity class name
// $loopMetaData = new loopMetaData($entityManager, $entityClassName);


?>