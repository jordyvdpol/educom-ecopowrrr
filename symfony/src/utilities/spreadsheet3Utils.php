<?php
namespace App\utilities;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Service\DummyDataService;
use Doctrine\ORM\EntityManagerInterface;


class spreadsheet3Utils {
  private $KlantenService;

  public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService)
  {
      $this->entityManager = $entityManager;
      $this -> DummyDataService = $DummyDataService;
  }

  public function maakSpreadsheet() {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $JaarlijkseGetallenGemeente = $this -> DummyDataService -> calcJaarlijkseGetallenGemeente();

    $worksheet
      ->setCellValue('A1', 'Gemeente')
      ->setCellValue('B1', 'Omzet')
      ->setCellValue('C1', 'Winst')
      ->setCellValue('D1', 'Aantal ingekochte KwH')
      ;
 
    $rowCount = 2;
    foreach ($JaarlijkseGetallenGemeente as $Gemeente => $value) {
        $worksheet->setCellValue('A' . $rowCount, $Gemeente);
        $worksheet->setCellValue('B' . $rowCount, $value['omzet']);
        $worksheet->setCellValue('C' . $rowCount, $value['winst']);
        $worksheet->setCellValue('D' . $rowCount, $value['KwH']);
        $rowCount++;
        }
      

    $headerStyle = [
      'font' => ['bold' => true],
      'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'color' => ['rgb' => 'FFA07A'],
      ],
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
    ];
    $worksheet->getStyle('A1:J1')->applyFromArray($headerStyle);
  
    $contentStyle = [
      'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
    ];
    $worksheet->getStyle('A2:J'.$rowCount)->applyFromArray($contentStyle);

    

    $writer = new Xlsx($spreadsheet);
    $filename = '/Applications/XAMPP/xamppfiles/htdocs/educom-ecopowrrr/symfony/spreadsheets/example3.xlsx';
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




