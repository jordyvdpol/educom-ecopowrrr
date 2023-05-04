<?php
namespace App\utilities;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Service\KlantenService;
use App\Service\DummyDataService;
use Doctrine\ORM\EntityManagerInterface;


class spreadsheet1Utils {
  private $KlantenService;

  public function __construct(EntityManagerInterface $entityManager, KlantenService $KlantenService, DummyDataService $DummyDataService)
  {
      $this->entityManager = $entityManager;
      $this->KlantenService = $KlantenService;
      $this -> DummyDataService = $DummyDataService;
  }

  public function maakSpreadsheet() {
    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();
    $klantData = $this -> KlantenService -> getAllKlantenData();
    $JaarlijkseOmzet = $this -> DummyDataService -> calcJaarlijkseOmzetKlant();


    foreach ($klantData as &$customer) {
      $id = $customer['id'];
      if (isset($JaarlijkseOmzet[$id])) {
        foreach ($JaarlijkseOmzet[$id] as $year => $data) {
            $customer[$year] = $data;
        }
      }
    }

    $worksheet
      ->setCellValue('A1', 'Klantnummer')
      ->setCellValue('B1', 'Naam')
      ->setCellValue('C1', 'postcode')
      ->setCellValue('D1', 'huisnummer')
      ->setCellValue('E1', 'stad')
      ->setCellValue('F1', 'gemeente')
      ->setCellValue('G1', 'provincie')
      ->setCellValue('H1', 'jaar')
      ->setCellValue('I1', 'omzet')
      ->setCellValue('J1', 'ingekochte KwH')
      ;
 
    $rowCount = 2;
    foreach ($klantData as $klant) {
      foreach ($klant as $key => $value) {
        if (is_numeric($key)) {
          $worksheet->setCellValue('A' . $rowCount, $klant['id']);
          $worksheet->setCellValue('B' . $rowCount, $klant['voornaam'] . ' ' . $klant['achternaam']);
          $worksheet->setCellValue('C' . $rowCount, $klant['postcode']);
          $worksheet->setCellValue('D' . $rowCount, $klant['huisnummer']);
          $worksheet->setCellValue('E' . $rowCount, $klant['stad']);
          $worksheet->setCellValue('F' . $rowCount, $klant['gemeente']);
          $worksheet->setCellValue('G' . $rowCount, $klant['provincie']);
          $worksheet->setCellValue('H' . $rowCount, $klant[$key]['jaar']);
          $worksheet->setCellValue('I' . $rowCount, $klant[$key]['omzet']);
          $worksheet->setCellValue('J' . $rowCount, $klant[$key]['KwH']);
          $rowCount++;
        }
      }
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
    $filename = '/Applications/XAMPP/xamppfiles/htdocs/educom-ecopowrrr/symfony/spreadsheets/example.xlsx';
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




