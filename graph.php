<?php
include_once __DIR__ . '/composer/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if(isset($_POST['submit'])) {
    $name = $_POST['insName'];
    $subject = $_POST['sub'];
    $sem = $_POST['sem'];
    $year = $_POST['year'];

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    $spreadsheet = $reader->load("./software/new.xlsx");

    $sheetData = $spreadsheet->getSheet(0)->toArray();

    $final = [];

    foreach($sheetData as $row) {
      if($row[11] == 'NC') {
        continue;
      }
      else {
        $final[] = $row[11];
      }
    }

    array_splice($final, 0, 1);

    sort($final);
    $count = array_count_values($final);

    $graph = [];

    $len = count($count);  // Length of the array, use in for loop
    $x = array_keys($count);  //get only keys from an array
    $y = array_values($count);  // get values from an array
    $max = max($y)+1;  // use for y-axis of graph

    $max_mark = max($x);
    $min_mark = min($x);

    // for average
    $avg = array_sum($final) / count($final);

    $fAvg = ceil($avg);  // round up the avg value

    for($i=0; $i<$len; $i++) {
      $push = "{x:" . $x[$i] . ", " . "y:" . $y[$i] . "}";
      array_push($graph,$push);
    }
    $fGraph = implode(",",$graph);
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
    body{
      font-family: sans-serif;
    }
    .wrapper{
      height: 660px !important;
    }
  </style>

</head>
<body>
  <div class="col-lg-12 d-flex flex-column align-items-center m-auto mt-4">
    <?php echo "<h2 class='text-center my-3'>$subject- Final Grading - $sem $year,Inst:$name</h2>" ?>
    <div class="wrapper p-4 border border-dark">
      <canvas id="myChart" width="1800" height="600"></canvas>
    </div>
    <div class="col-lg-6 text-start mt-4 w-100 px-4 text-primary">
      <h6>Average of Marks: <?php echo $fAvg; ?></h6>
      <h6>Maximum of Marks: <?php echo $max_mark; ?></h6>
      <h6>Minimum of Marks: <?php echo $min_mark; ?></h6>
      <button class="btn mx-2 btn-outline-primary btn-sm float-end">Print Graph</button>
      <button class="btn btn-outline-secondary btn-sm float-end">Graph Range</button>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script type="text/javascript">
  $(document).ready(function() {
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
      data: {
          labels: ['Red','Blue','Green','Yellow','purple ','orange'],
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
              barPercentage:0.6,
              categoryPercentage: 0.6,

          }],
        },
        options: {
          scales: {
            x: {
              type: 'linear',
              reverse: true,
              offset:false,
              border: {
                display:true,
                color: 'black',
              },
              grid: {
                color:'black',
                display: true,
                offset: false,
                drawOnChartArea: false,

              },
              ticks: {
                stepSize: 1,
                maxRotation:90,
                minRotation:90,
                callback: function (value, index, values) {
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
                color:'red'

              }
            },
            y: {
              beginAtZero: true,
              border: {
                display:true,
                color: 'black',
              },
              grid: {
                color:'black',
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
          plugins:{
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

</script>
</body>
</html>
