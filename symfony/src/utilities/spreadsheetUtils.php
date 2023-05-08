<?php
namespace App\utilities;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Service\KlantenService;
use Doctrine\ORM\EntityManagerInterface;


class spreadsheetUtils {
  private $KlantenService;

  public function __construct(EntityManagerInterface $entityManager, KlantenService $KlantenService)
  {
      $this->entityManager = $entityManager;
      $this->KlantenService = $KlantenService;
  }

  public function maakSpreadsheet() {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $klantData = $this -> KlantenService -> getAllKlantenData();

    $worksheet
      ->setCellValue('A1', 'Naam');
      $rowCount = 2;
      $i = 0;
      while ($row = $klantData){
        dump($row);
        $worksheet->setCellValue('A'.$rowCount, $row[$i]['voornaam']);
        $rowCount++;
        $i++;
      }
  

      $writer = new Xlsx($spreadsheet);
      $filename = 'C:\xampp\htdocs\educom-ecopowrrr\symfony\tests\example.xlsx';
      $writer->save($filename);
      if (file_exists($filename)) {
          echo "Excel file generated successfully at $filename";
      } else {
          echo "Error generating Excel file";
      }

    return;
  }
}

// functie test command line:
// php -r "require 'C:\xampp\htdocs\educom-ecopowrrr\symfony\src\utilities\spreadsheetUtils.php'; echo json_encode(App\utilities\spreadsheetUtils::maakSpreadsheet());"
// composer require phpoffice/phpspreadsheet
// npm install chart.js regression
// npm install regression
?>