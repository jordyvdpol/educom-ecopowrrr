<?php
namespace App\utilities;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Service\DummyDataService;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend as ChartLegend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Properties;
use PhpOffice\PhpSpreadsheet\Chart\Title;

use PhpOffice\PhpSpreadsheet\Spreadsheet;


class spreadsheet2Utils {
  private $KlantenService;
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager, DummyDataService $DummyDataService)
  {
      $this->entityManager = $entityManager;
      $this -> DummyDataService = $DummyDataService;
  }
  

    public function maakSpreadsheet() {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $maandelijkseOmzet = $this -> DummyDataService -> calcMaandelijkseOmzet();
        $jaarlijkseOmzet = $this -> DummyDataService -> calcJaarlijkseOmzet();
    
        $worksheet->fromArray(
            [
                ['', 2010, 2011, 2012],
                ['Q1', 12, 15, 21],
                ['Q2', 56, 73, 86],
                ['Q3', 52, 61, 69],
                ['Q4', 30, 32, 28],
            ]
        );
        
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2010
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2012
        ];
        $dataSeriesLabels[0]->setFillColor('FF0000');

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
        ];
        $dataSeriesValues[2]->setLineWidth(60000 / Properties::POINTS_WIDTH_MULTIPLIER);
        
        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            null, // plotGrouping, was DataSeries::GROUPING_STACKED, not a usual choice for line chart
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
        
        $plotArea = new PlotArea(null, [$series]);

        // Set the chart legend
        $legend = new ChartLegend(ChartLegend::POSITION_TOPRIGHT, null, false);
        
        $title = new Title('Test Line Chart');
        $yAxisLabel = new Title('Value ($k)');
        
        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            DataSeries::EMPTY_AS_GAP, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );
        
        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A6');
        $chart->setBottomRightPosition('H25');
        
        // Add the chart to the worksheet
        
        $worksheet->addChart($chart);
        
        $writer = new Xlsx($spreadsheet);
        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');

        $filename = '/Applications/XAMPP/xamppfiles/htdocs/educom-ecopowrrr/symfony/spreadsheets/example2.xlsx';
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




