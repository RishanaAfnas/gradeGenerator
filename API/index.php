<?php
session_start();

require_once('../composer/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


if(isset($_FILES['doc'])) {
    $doc_name =  $_FILES['doc']['name'];
    $doc_tmp = $_FILES['doc']['tmp_name'];
    $doc_type = $_FILES['doc']['type'];
    $ext = pathinfo($doc_name, PATHINFO_EXTENSION);
    echo "<pre>";
    print_r($doc_name);

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    if(!$ext == 'xlsx' || !$ext == 'doc' || !$ext == 'docx' ) {

        echo"<h2>Please choose the document file.</h2>";
        die;
    }
    else {

        $spreadsheet = $reader->load("$doc_tmp");

        $sheetData = $spreadsheet->getSheet(0)->toArray();

        $indexesToRemove = array(0,1,2,3,5);

        foreach($indexesToRemove as $index) {
            unset($sheetData[$index]);
        }

        $len = count($sheetData);

        foreach ($sheetData as &$row) {
            array_splice($row, 0, 1); // Remove the first column from each row
        }

        unset($row); // Unset the reference

        $final = array_values($sheetData);

        $final[0][0] = 'IDNO';
        $final[0][4] = 'Mid-Sem Total';
        $final[0][7] = 'Quiz-1/Assign-1';
        $final[0][8] = 'Pre Compre Total';
        $final[0][10] = 'Grand Total';
        $final[0][11] = 'Final Total';


        // For Writing
        $newSpreadsheet = new Spreadsheet();

        $newSheet = $newSpreadsheet->getSheet(0)->fromArray($final);

        // Apply formatting to all columns
        $columnCount = $newSheet->getHighestColumn();
        $columnCountIndex = Coordinate::columnIndexFromString($columnCount);
        for ($column = 1; $column <= $columnCountIndex; $column++) {
            $columnLetter = Coordinate::stringFromColumnIndex($column);

            // Wrap text
            $newSheet->getStyle($columnLetter . '1:' . $columnLetter . $newSheet->getHighestRow())
                ->getAlignment()
                ->setWrapText(true);

            // Align text center horizontally and vertically
            $newSheet->getStyle($columnLetter . '1:' . $columnLetter . $newSheet->getHighestRow())
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            // Auto fit column width
            $newSheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $newSheet->getStyle('1:1')->getFont()->setBold(true);
        $newSheet->getStyle('1:1')->getFont()->setSize(9);

        $writer = new Xlsx($newSpreadsheet);

        $writer->save('../software/software.xlsx');
            }
        }
      else{
        echo "hello";
      }
