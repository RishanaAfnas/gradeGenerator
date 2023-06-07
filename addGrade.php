<?php
session_start();


// function destroySession(){
//     // Unset all session variables
//     session_unset();
//     // Destroy the session
//     session_destroy();
//     header("Location: index.html");
//     exit(); // Make sure to exit afte
// }
function destroySession()
{
    // Unset all session variables
    session_unset();
    // Destroy the session
    session_destroy();
    // Redirect to index.html using JavaScript 
    echo "<script>window.location.href = 'index.html';</script>";
    exit(); // Make sure to exit after the redirection
}

// ...

if (isset($_POST['destroy_session'])) {
    destroySession();
}



$name = $_SESSION['name'];
$subject = $_SESSION['subject'];
$sem = $_SESSION['sem'];
$year = $_SESSION['year'];


include_once __DIR__ . '/composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if (isset($_POST['submit'])) {


    $startA = $_POST['startA'];
    // $endA = $_POST['endA'];
    $startB = $_POST['startB'];
    // $endB = $_POST['endB'];
    $startC = $_POST['startC'];
    // $endC = $_POST['endC'];
    $startD = $_POST['startD'];
    // $endD = $_POST['endD'];
    $startE = $_POST['startE'];
    // $endE = $_POST['endE'];
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
    if (($data >= $startA) && ($data <= 100)) {
        $countA++;
    } elseif (($data >= $startB) && ($data <= $startA - 1)) {
        $countB++;
    } elseif (($data >= $startC) && ($data <= $startB - 1)) {
        $countC++;
    } elseif (($data >= $startD) && ($data <= $startC - 1)) {
        $countD++;
    } elseif (($data >= $startE) && ($data <= $startD - 1)) {
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
//calculate CGPA

$gradePointA = "4.0";
$gradePointB = "3.0";
$gradePointC = "2.0";
$gradePointD = "1.0";
$gradePointE = "0.0";

$totalGradePoints = ($countA * $gradePointA) + ($countB * $gradePointB) + ($countC * $gradePointC) + ($countD * $gradePointD) + ($countE * $gradePointE);

$totalStudents = (array_sum($newArray));
$CGPA = $totalGradePoints / $totalStudents;
$rounded_cgpa = round($CGPA, 1);









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
    <script>
        function confirmClose() {
  var confirmBox = confirm("Are you sure you want to close?");
  if (confirmBox) {
    
    window.location.href = "index.html";
  } else {
    
    event.preventDefault();
  }
}

    </script>
    <style>
        tr,
        td {
            text-align: center;
        }

        .tables {

            margin-left: 33%;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .button-container button {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container ">
        <?php echo "<ul class='text-center my-4 fw-bold' style='list-style-type: none;'>";
        echo "<li style='font-size: 20px;'>$subject - Final Grading - $sem $year</li>";
        echo "<li style='font-size: 20px;'>Instructor: $name</li>";
        echo "</ul>"; ?>
        <h4 class="text-center text-primary mt-3">Grade Ranges</h4>
        <div class=tables>
            <table class=" mt-4 table table-bordered  border-dark w-50 hadow p-3 mb-5 bg-body rounded ">
                <thead class="table-success">
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
                        <td><?php echo '100' ?></td>
                    </tr>
                    <tr>

                        <td>B</td>
                        <td><?php echo $startB ?></td>
                        <td><?php echo $startA - 1 ?></td>
                    </tr>
                    <tr>

                        <td>C</td>
                        <td><?php echo $startC ?></td>
                        <td><?php echo $startB - 1 ?></td>
                    </tr>
                    <tr>

                        <td>D</td>
                        <td><?php echo $startD ?></td>
                        <td><?php echo $startC - 1 ?></td>
                    </tr>
                    <tr>

                        <td>E</td>
                        <td><?php echo $startE ?></td>
                        <td><?php echo $startD - 1 ?></td>
                    </tr>
                </tbody>
            </table>
            <table class=" mt-5 table table-bordered border-primary w-50 hadow p-3 mb-5 bg-body rounded">
                <thead class="table-success">
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
                    <tr>

                        <td class="fw-bold">CGPA</td>
                        <td><?php echo $rounded_cgpa; ?></td>

                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="button-container">
            <button class="btn btn-primary" onclick="downloadTables()">Download Tables</button>

            <form action="" method="post" id="destroy-session-form">
                
                <div style="text-align: right;">
                    <button class="btn btn-danger" type="submit" name="destroy_session" value="Destroy Session" onclick="confirmClose()">Close</button>
                </div>
            </form>
        </div>




    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
        // function downloadTables() {
        //     // Select the container element that wraps the tables
        //     var container = document.querySelector('.container');

        //     // Use html2canvas library to capture the container as an image
        //     html2canvas(container).then(function(canvas) {
        //         // Create a temporary link element and set its attributes
        //         var link = document.createElement('a');
        //         link.href = canvas.toDataURL(); // Convert canvas to data URL
        //         link.download = 'tables.png'; // Set the desired filename (e.g., tables.png)

        //         // Append the link to the document and simulate a click
        //         document.body.appendChild(link);
        //         link.click();

        //         // Clean up by removing the link element
        //         document.body.removeChild(link);
        //     });
        // }
        function downloadTables() {
            // Select the container element that wraps the tables
            var container = document.querySelector('.container');

            // Use html2canvas library to capture the container as an image
            html2canvas(container).then(function(canvas) {
                // Convert canvas to data URL
                var imgData = canvas.toDataURL('image/png');

                // Calculate the width and height of the PDF document
                var docWidth = canvas.width / 8.0;
                var docHeight = canvas.height / 5.0;

                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('p', 'mm', [docWidth, docHeight]);
                // Create a new jsPDF instance
                // var pdf = new jsPDF('p', 'mm', [docWidth, docHeight]);

                // Add the captured image to the PDF document
                doc.addImage(imgData, 'PNG', 0, 0, docWidth, docHeight);

                // Save the PDF document
                doc.save('tables.pdf');
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
        if (($marks >= $startA) && ($marks <= 100)) {
            $newgrade = 'A';
        } elseif (($marks >= $startB) && ($marks <= $startA - 1)) {
            $newgrade = 'B';
        } elseif (($marks >= $startC) && ($marks <= $startB - 1)) {
            $newgrade = 'C';
        } elseif (($marks >= $startD) && ($marks <= $startC - 1)) {
            $newgrade = 'D';
        } elseif (($marks >= $startE) && ($marks <= $startD - 1)) {
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
