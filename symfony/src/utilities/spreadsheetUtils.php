<?php
namespace App\utilities;
require 'vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class spreadsheetUtils {
    public static function maakSpreadsheet(){
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A1', 'Name')
          ->setCellValue('B1', 'Email')
          ->setCellValue('A2', 'John Doe')
          ->setCellValue('B2', 'john.doe@example.com');

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse();

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="example.xlsx"');

        $response->setCallback(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->save('test.xlsx');
        });
        return $response;
    }
}

// functie test command line:
// php -r "require 'C:\xampp\htdocs\educom-ecopowrrr\symfony\src\utilities\spreadsheetUtils.php'; echo json_encode(App\utilities\spreadsheetUtils::maakSpreadsheet());"
// composer require phpoffice/phpspreadsheet
// npm install chart.js regression
// npm install regression
?>




