<?php 
session_start();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Grade generator</title>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"
    />
    <!-- Include SweetAlert2 CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <!-- partial:index.partial.html -->
    <div class="content" style="height:100vh !important;">
      <form
        class="form"
        action="main.php"
        method="POST"
        enctype="multipart/form-data"
      >
        <div class="file-upload-wrapper" data-text="Select your file!">
          <input
            type="file"
            class="file-upload-field"
            value=""
            name="doc"
            accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            required
          />
        </div>
        <div class="btn-wrapper">
          <button class="upload-btn" id="btn1" type="submit" name="submit">
            Submit
          </button>
          <button class="show-btn disabled" id="btn2" type="button" disabled>
            Show Graph
          </button>
        </div>
      </form>
    </div>

    <!-- partial -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="script.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://code.jquery.com/jquery-3.7.0.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.3/dist/sweetalert2.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function () {
        $("form").on("submit", function (e) {
          var formData = new FormData(this);
          e.preventDefault();
          $.ajax({
            url: "./API/index.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (res) {
              Swal.fire({
                title: "Submit ",
                text: "Are you sure you want to submit ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                reverseButtons: true,
              }).then((result) => {
                if (result.isConfirmed) {
                  // Enable the "Show Graph" button
                  document.getElementById("btn2").disabled = false;

                  Swal.fire({
                    title: " Submitted!",
                    text: " submitted successfully. Press the Show Graph button to proceed.",
                    icon: "success",
                    confirmButtonText: "OK",
                  });
                }
              });
            },
            error: function (err) {
              console.log(err);
            },
          });
        });

        $("#btn2").on("click", async function () {
          const { value: formValues } = await Swal.fire({
            title: "Graph Detail",
            html: `
          <form class="d-flex flex-column w-75 m-auto align-items-center" action="./graph.php" method="POST" id="form">
            
  
            <input type="text" class="form-control mt-4" name="insName" placeholder="Instructor Name" required>
            <input type="text" class="form-control mt-4 " name="sub" placeholder="Subject" required>
            <input type="text" class="form-control mt-4" name="subCode" placeholder="Subject Code" required>
            <select class="form-select mt-4" name="sem" aria-label="Default select example" required>
              <option value="" selected disabled>Choose Semester</option>
              <option value="Ist Sem">Ist Sem</option>
              <option value="IInd Sem">IInd Sem</option>
              <option value="IIIrd Sem">IIIrd Sem</option>
            </select>
            <select class="form-select mt-4" name="year" aria-label="Default select example" required>
              <option value=""selected disabled>Choose Year</option>
              <option value="2021-22">2021-22</option>
              <option value="2022-23">2022-23</option>
              <option value="2023-24">2023-24</option>
            </select>
            <div class="d-flex justify-content-between mt-4">
              <button class="btn btn-primary" name="submit" type="submit">Submit</button>
              <button class="btn btn-danger ms-3" type="reset">Cancel</button>
            </div>
          </form>
        `,
       
            allowOutsideClick: false,
            showConfirmButton: false,
            focusConfirm: false,
            showCloseButton: true
          });
         
        });
      });
    </script>
  </body>
</html>
