<?php
// namespace App\utilities;


// use Doctrine\ORM\Mapping\ClassMetadata;
// use Doctrine\ORM\EntityManagerInterface;

// class loopMetaData{
//     private ClassMetadata $metadata;
//     private EntityManagerInterface $entityManager;

//     public function __construct(EntityManagerInterface $entityManager)
//     {
//         $this->entityManager = $entityManager;
//         $this->metadata = $this->entityManager->getClassMetadata(DummyData::class);
//     }

//     public  function loopData($data) {
//         $result = [];
    
//         foreach ($this->metadata->fieldMappings as $key => $mapping) {
//             $type = $mapping['type'];
//             $func = 'get' . ucwords(str_replace('_', '', $key));
    
//             if (method_exists($data, $func)) {
//                 $value = $data->$func();
//                 $result[$key] = $value;
//             }
//         }
//         return $result;      
//     }
// }

// ?>