<?php
namespace App\utilities;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class spreadsheetUtils {
  public static function maakSpreadsheet() {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

    $worksheet->setCellValue('A1', 'Name')
      ->setCellValue('B1', 'Email')
      ->setCellValue('A2', 'John Doe')
      ->setCellValue('B2', 'john.doe@example.com');

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




