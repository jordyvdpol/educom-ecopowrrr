<?php 

// public function loopData($data) {
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
    

//     public function getAllDummyData() {
    

//         $data = $this->KlantenRepository->findAllById();
//         $id =[];
//         foreach ($data as $key){
//             array_push($id, $key['id']);
//             dump($key['id']);
//             $data = $this -> DummyDataRepository->findAllKlantnummer($key['id']);
//             dump($data);
//             if (!$data) {
//                 dump(false);
//             }else if (count($data)< 2) {
//                 $result = DummyDataService::loopData($data);
//                 // dump($result);
//             }
//         }
