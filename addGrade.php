<?php

include_once __DIR__ . '/composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (isset($_POST['submit'])) {


    $startA = $_POST['startA'];
    $endA = $_POST['endA'];
    $startB = $_POST['startB'];
    $endB = $_POST['endB'];
    $startC = $_POST['startC'];
    $endC = $_POST['endC'];
    $startD = $_POST['startD'];
    $endD = $_POST['endD'];
    $startE = $_POST['startE'];
    $endE = $_POST['endE'];
}

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$spreadsheet = $reader->load('C:\xampp\htdocs\grade\new grade-generator\software\software.xlsx');

$sheetData = $spreadsheet->getSheet(0)->toArray();

$final = [];

foreach ($sheetData as $row) {
    if ($row[11] == 'NC') {
        continue;
    } else {
        $final[] = $row[11];
    }
}
array_splice($final, 0, 1);



$countA = 0;
$countB = 0;
$countC = 0;
$countD = 0;
$countE = 0;


foreach ($final as $data) {
    if (($data >= $startA) && ($data <= $endA)) {
        $countA++;
    } elseif (($data >= $startB) && ($data <= $endB)) {
        $countB++;
    } elseif (($data >= $startC) && ($data <= $endC)) {
        $countC++;
    } elseif (($data >= $startD) && ($data <= $endD)) {
        $countD++;
    } elseif (($data >= $startE) && ($data <= $endE)) {
        $countE++;
    }
}
$gradeCount = array(
    $countA,
    $countB,
    $countC,
    $countD,
    $countE,
);
$newArray = [];
foreach ($gradeCount as $grade) {
    array_push($newArray, $grade); //calculate sum
}










?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        tr,
        td {
            text-align: center;
        }

        .tables {

            margin-left: 33%;
        }
    </style>
</head>

<body>
    <div class="container ">
        <h1 class="text-center text-primary mt-3">Grade Ranges</h1>
        <div class=tables>
            <table class=" mt-5 table table-bordered  border-dark w-50">
                <thead>
                    <tr>

                        <th scope="col">Grade</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td>A</td>
                        <td><?php echo $startA ?></td>
                        <td><?php echo $endA ?></td>
                    </tr>
                    <tr>

                        <td>B</td>
                        <td><?php echo $startB ?></td>
                        <td><?php echo $endB ?></td>
                    </tr>
                    <tr>

                        <td>C</td>
                        <td><?php echo $startC ?></td>
                        <td><?php echo $endC ?></td>
                    </tr>
                    <tr>

                        <td>D</td>
                        <td><?php echo $startD ?></td>
                        <td><?php echo $endD ?></td>
                    </tr>
                    <tr>

                        <td>E</td>
                        <td><?php echo $startE ?></td>
                        <td><?php echo $endE ?></td>
                    </tr>
                </tbody>
            </table>
            <table class=" mt-5 table table-bordered border-primary w-50">
                <thead>
                    <tr>

                        <th scope="col">Grade</th>
                        <th scope="col">No.Of students</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td>A</td>
                        <td><?php echo $countA ?></td>

                    </tr>
                    <tr>

                        <td>B</td>
                        <td><?php echo $countB ?></td>

                    </tr>
                    <tr>

                        <td>C</td>
                        <td><?php echo $countC ?></td>

                    </tr>
                    <tr>

                        <td>D</td>
                        <td><?php echo $countD ?></td>

                    </tr>
                    <tr>

                        <td>E</td>
                        <td><?php echo $countE ?></td>

                    </tr>
                    <tr>

                        <td class="fw-bold">Total</td>
                        <td><?php echo (array_sum($newArray)); ?></td>

                    </tr>
                </tbody>
            </table>
        </div>
        <button class="btn btn-primary" onclick="downloadTables()">Download Tables</button>


    </div>

    <script>
        function downloadTables() {
            // Select the container element that wraps the tables
            var container = document.querySelector('.container');

            // Use html2canvas library to capture the container as an image
            html2canvas(container).then(function(canvas) {
                // Create a temporary link element and set its attributes
                var link = document.createElement('a');
                link.href = canvas.toDataURL(); // Convert canvas to data URL
                link.download = 'tables.png'; // Set the desired filename (e.g., tables.png)

                // Append the link to the document and simulate a click
                document.body.appendChild(link);
                link.click();

                // Clean up by removing the link element
                document.body.removeChild(link);
            });
        }
    </script>
    <?php
    $newFinal = [];
    $headerRow = ['IDNO', 'NAME', 'SCORE', 'Grade']; // Replace 'Column1', 'Column2', 'Column3' with the actual column names
    $newFinal[] = $headerRow;

    foreach ($sheetData as $key => $row) {
        if ($key === 0) {
            continue;
        }

        $firstColumns = array_slice($row, 0, 2);       // Extract the first two columns
        $lastColumn = array_slice($row, -1, 1);
        $newRow = array_merge($firstColumns, $lastColumn);       // Extract the last column

        $newFinal[] = $newRow;
    }

    foreach ($newFinal as $key => $student) {
        // Get the marks of the student
        $marks = $student[2];


        $newgrade = '';
        // Determine the grade based on the marks using if-else statements
        if (($marks >= $startA) && ($marks <= $endA)) {
            $newgrade = 'A';
        } elseif (($marks >= $startB) && ($marks <= $endB)) {
            $newgrade = 'B';
        } elseif (($marks >= $startC) && ($marks <= $endC)) {
            $newgrade = 'C';
        } elseif (($marks >= $startD) && ($marks <= $endD)) {
            $newgrade = 'D';
        } elseif (($marks >= $startE) && ($marks <= $endE)) {
            $newgrade = 'E';
        }
        $newFinal[$key][] = $newgrade;
    }



    $newSpreadsheet = new Spreadsheet();

    $newSheet = $newSpreadsheet->getSheet(0)->fromArray($newFinal);
    $writer = new Xlsx($newSpreadsheet);

    $writer->save('C:\xampp\htdocs\grade\new grade-generator\software\changed.xlsx');




    exit;

    ?>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.0/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script>

</body>

</html>
