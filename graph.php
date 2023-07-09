<?php
session_start();

include_once __DIR__ . '/composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

if (isset($_POST['submit'])) {
  $name = $_POST['insName'];
  $_SESSION['name'] = $name;

  $subject = $_POST['sub'];
  $_SESSION['subject'] = $subject;
  $sem = $_POST['sem'];
  $_SESSION['sem'] = $sem;
  $year = $_POST['year'];
  $_SESSION['year'] = $year;

  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

  $spreadsheet = $reader->load('./software/software.xlsx');

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


  sort($final);

  $count = array_count_values($final);


  $graph = [];

  $len = count($count);  // Length of the array, use in for loop
  $x = array_keys($count);
  //get only keys from an array
  $y = array_values($count);
  // get values from an array
  $max = max($y) + 1;
  // use for y-axis of graph


  $max_mark = max($x);
  $min_mark = min($x);

  // for average
  $avg = array_sum($final) / count($final);

  $fAvg = ceil($avg);  // round up the avg value

  for ($i = 0; $i < $len; $i++) {
    $push = "{x:" . $x[$i] . ", " . "y:" . $y[$i] . "}";
    array_push($graph, $push);
  }
  $fGraph = implode(",", $graph);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Graph</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">



  <style>
    body {
      font-family: sans-serif;
    }

    .wrapper {
      height: 500px !important;
    }

    table {
      margin: 10px;
      padding: 10px;
    }

    tr,
    td {
      margin: 0;
      padding: 5px;
    }

    th {
      text-align: center;
    }
  </style>

</head>

<body>
  <!-- Modal -->
  <div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <form action="addGrade.php" method="POST">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Enter Grade Ranges</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>FROM</th>
                  <th>TO</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>A</td>
                  <td><input type="number" id="startA" name="startA" required></td>
                  <td><input type="number"id="endA" name="endA"></td>
                </tr>
                <tr>
                  <td>B</td>
                  <td><input type="number" id="startB"  name="startB" required></td>
                  <td><input type="number"  id="endB" name="endB"></td>
                </tr>
                <tr>
                  <td>C</td>
                  <td><input type="number" id="startC" name="startC" required></td>
                  <td><input type="number"id="endC" name="endC"></td>
                </tr>
                <tr>
                  <td>D</td>
                  <td><input type="number" id="startD" name="startD" required></td>
                  <td><input type="number" id="endD" name="endD"></td>
                </tr>
                <tr>
                  <td>E</td>
                  <td><input type="number" id="stratE" name="startE" required></td>
                  <td><input type="number" id="endE" name="endE"></td>
                </tr>
              </tbody>
            </table>

          </div>
          <div class="modal-footer">


            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="submit" id="btnGrade">OK</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="col-lg-12 d-flex flex-column align-items-center m-auto mt-4">
    <?php echo "<ul class='text-center my-3 fw-bold' style='list-style-type: none;'>";
        echo "<li style='font-size: 20px;' class='text-uppercase' >$subject - Final Grading - $sem $year</li>";
        echo "<li style='font-size: 20px;'>Instructor: $name</li>";
        echo "</ul>"; ?>
    <div class="wrapper p-4 border border-dark">
      <canvas id="myChart" width="1800" height="600"></canvas>
    </div>
    <div class="col-lg-6 text-start mt-4 w-100 px-4 text-primary fw-bold">
      <h6>Average of Marks: <?php echo $fAvg; ?></h6>
      <h6>Maximum of Marks: <?php echo $max_mark; ?></h6>
      <h6>Minimum of Marks: <?php echo $min_mark; ?></h6>
      <button type=button class="btn mx-2 btn-outline-primary btn-sm float-end" id="downloadBtn" onclick="downloadGraph()">Download Graph</button>

      <button type="button" class="btn mx-2 btn-outline-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Grade Ranges
      </button>




    </div>
  </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
  


  // Function to update the next field based on the current input
  function updateNextField(currentFieldId, nextFieldId) {
    const currentField = document.getElementById(currentFieldId);
    const nextField = document.getElementById(nextFieldId);
    
    // Subtract 1 from the value of the current field and set it as the value of the next field
    nextField.value = currentField.value ? parseInt(currentField.value) - 1 : '';
  }

  // Add event listeners to the current fields to update the next fields
  document.getElementById('startA').addEventListener('input', function() {
    updateNextField('startA', 'endB');
  });
  document.getElementById('startB').addEventListener('input', function() {
    updateNextField('startB', 'endC');
  });
  document.getElementById('startC').addEventListener('input', function() {
    updateNextField('startC', 'endD');
  });
 document.getElementById('startD').addEventListener('input',function() {
  updateNextField('startD','endE');
 });
});
 
</script>


  <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      var ctx = document.getElementById('myChart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Red', 'Blue', 'Green', 'Yellow', 'purple ', 'orange'],
          datasets: [{
            data: [
              <?php
              echo $fGraph;
              ?>
            ],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)'
            ],
            borderWidth: 1,
            barPercentage: 0.6,
            categoryPercentage: 0.6,

          }],
        },
        options: {
          scales: {
            x: {
              type: 'linear',
              reverse: true,
              offset: false,
              border: {
                display: true,
                color: 'black',
              },
              grid: {
                color: 'black',
                display: true,
                offset: false,
                drawOnChartArea: false,

              },
              ticks: {
                stepSize: 1,
                maxRotation: 90,
                minRotation: 90,
                callback: function(value, index, values) {
                  if (value % 5 === 0) {
                    return value.toString();
                  }
                  return '';
                },
              },
              max: 100,
              min: 0,
              title: {
                display: true,
                text: 'Marks',
                color: 'red'

              }
            },
            y: {
              beginAtZero: true,
              border: {
                display: true,
                color: 'black',
              },
              grid: {
                color: 'black',
                display: true,
                offset: false,
              },
              title: {
                display: true,
                text: 'No. Of Students',
                color: 'red',
              },
              ticks: {
                stepSize: 1,
                stepColor: 'black',
              },
              max: <?php echo $max; ?>,
            },
          },
          plugins: {
            tooltip: {
              enabled: false,
            },
            legend: {
              display: false
            },
          },
        },
      });
    });

    // function downloadGraph() {
    //   var canvas = document.getElementById("myChart");
    //   var dataURL = canvas.toDataURL("image/png");
    //   var link = document.createElement("a");
    //   link.href = dataURL;
    //   link.download = "graph.png";
    //   link.click();
    // }
    //download as pdf
    function downloadGraph() {
      var canvas = document.getElementById("myChart");
      var dataURL = canvas.toDataURL("image/png");

      // Create a new jsPDF instance
      // var doc = new jsPDF();
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      // Add the image as a PDF element
      doc.addImage(dataURL, "PNG", 10, 10, 190, 100); // Adjust the position and dimensions as needed

      // Save the PDF file
      doc.save("graph.pdf");
    }
  </script>
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</body>

</html>