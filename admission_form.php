<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <style>
    body {
      background: linear-gradient(135deg, 
        rgba(0, 180, 70, 0.8), 
        rgba(0, 220, 90, 0.6), 
        rgba(255, 140, 0, 0.6), 
        rgba(0, 150, 255, 0.6), 
        rgba(200, 0, 200, 0.6)
      ); 
      background-size: 400% 400%; 
      animation: gradientAnimation 15s ease infinite; 
      font-family: 'Arial', sans-serif;
      color: #333;
    }

    @keyframes gradientAnimation {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .main-header {
      color: black;
      text-align: center;
      padding: 20px;
      font-size: 24px;
      font-weight: bold;
    }

    .content {
      padding: 20px;
      border-radius: 8px;
      background-color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      margin: 20px auto; 
      max-width: 750px; 
    }

    button[type="submit"] {
      background-color: #00a65a;
      border-color: #00a65a;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 1rem;
    }

    button[type="submit"]:hover {
      background-color: #004d00;
    }

    .footer {
      color: black;
      text-align: center;
      padding: 10px;
      font-size: 0.9rem;
      margin-top: 20px;
    }

    label {
      font-size: 0.8rem; 
      font-weight: bold; 
    }

    .alert-info {
      font-weight: bold;
    }

  </style>
</head>
<body>
  
  <header class="main-header">
    <div class="d-flex justify-content-start">
      <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='admission_portal.php'">
        <i class="bi bi-arrow-left"></i> <small>Back to Admission Portal</small>
      </button>
    </div>
  </header>

  <div class="container">
    <div class="content">
      <img id="sidebar-logo" src="dist/img/macayo_logo.png" alt="DepEd Logo" style="max-width: 100px; margin-left: auto; margin-right: auto; display: block;">
      <div class="text-center">
        <small>
          Address: Macayo Alcala, Pangasinan, Philippines<br>
          Phone: +63 123 456 7890<br>
          Email: info@macayo.edu.ph
        </small>
      </div>
      <img id="sidebar-logo" src="dist/img/macayo_title.png" alt="DepEd Logo" style="max-width: 350px; margin-left: auto; margin-right: auto; display: block;">
      <h4 class="mb-4 text-center">Enrollment Form</h4>
      <!-- Personal Information Section -->
<div class="alert alert-info" role="alert">Personal Information:</div>
<form role="form" method="POST" action="add_learner.php" enctype="multipart/form-data">
  <div class="box-body">

    <div class="form-group row">
      <div class="col-md-12">
        <label for="studentID">LRN no.:</label>
        <input name="lrn" type="text" class="form-control" maxlength="12" id="studentID" required onblur="checkLRN()">
        <small id="lrnError" class="text-danger" style="display: none;">This LRN is already in use.</small>
      </div>
    </div>

    <div class="form-group row">
      <div class="col-md-4">
        <label for="firstName">First Name:</label>
        <input name="fname" type="text" class="form-control" id="firstName" required>
      </div>
      <div class="col-md-4">
        <label for="middleName">Middle Name:</label>
        <input name="middle_name" type="text" class="form-control" id="middleName"> <!-- Added middle name field -->
      </div>
      <div class="col-md-4">
        <label for="lastName">Last Name:</label>
        <input name="lname" type="text" class="form-control" id="lastName" required>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-4">
        <label for="nameExtension">Name Extension (Jr, II, III):</label>
        <input name="name_extension" type="text" class="form-control" id="nameExtension"> <!-- Added name extension field -->
      </div>
      <div class="col-md-4">
        <label>Date of Birth:</label>
        <input type="text" class="form-control" name="dob" id="date_of_birth" required readonly>
      </div>
      <div class="col-md-4">
        <label>Address:</label>
        <input type="text" class="form-control" name="address" id="address" required>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-4">
        <label for="contnum">Contact Number:</label>
        <input name="contnum" type="text" class="form-control" id="contnum"> <!-- Added name extension field -->
      </div>
      <div class="col-md-4">
        <label>Religion:</label>
        <input type="text" class="form-control" name="religion" id="religion" required>
      </div>
      <div class="col-md-4">
        <label>Age:</label>
        <input type="text" class="form-control" name="age" id="age" required>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-6">
        <label for="gender">Gender:</label>
        <div class="radio mb-3">
          <label>
            <input type="radio" name="gender" value="Male" required> Male
          </label>
          <label>
            <input type="radio" name="gender" value="Female"> Female
          </label>
        </div>
      </div>
      <div class="col-md-6">
        <label for="studentType">Type of Learner:</label>
        <div class="radio mb-3">
          <label>
            <input type="radio" name="studentType" value="Old" required> Old
          </label>
          <label>
            <input type="radio" name="studentType" value="New Transferee"> New Transferee
          </label>
        </div>
      </div>
    </div>

    <!-- Move Guardian fields here -->
    <div class="form-group row">
      <div class="col-md-6"> <!-- Adjust the column width as needed -->
        <label for="guardianName">Guardian Name:</label>
        <input name="guardianName" type="text" class="form-control" id="guardianName" required style="font-size: 0.8rem;">
      </div>
      <div class="col-md-6"> <!-- Adjust the column width as needed -->
      <label>Guardian Relationship:</label>
<select class="form-control select2" name="guardian_relationship" id="guardianSelect" required onchange="toggleOtherGuardianInput()" style="font-size: 0.8rem;">
  <option value="">Select Guardian Relationship</option>
  <option value="Parent">Parent</option>
  <option value="Step Parent">Step Parent</option>
  <option value="Grandparent">Grandparent</option>
  <option value="Aunt">Aunt</option>
  <option value="Uncle">Uncle</option>
  <option value="Sibling">Sibling</option>
  <option value="Other">Other</option>
</select>
<input type="text" name="otherGuardian" id="otherGuardian" class="form-control mt-2" style="font-size: 0.8rem;" placeholder="Please specify if Other">

  </div>
</div>
   
    <br>
    
    <!-- Previous School Information Section -->
    <div class="alert alert-info" role="alert">Previous School Information:</div>

    <div class="form-group row">
      <div class="col-md-6">
        <label for="schoolAttended">Elementary School Attended:</label>
        <select class="form-control" name="schoolAttended" id="schoolAttended" required onchange="toggleOtherSchoolInput()">
          <option value="">Select Elementary School</option>
          <option value="Aliaga Elementary School">Aliaga Elementary School</option>
          <option value="Macayo Integrated School">Macayo Integrated School</option>
          <option value="San Vicente Elementary School">San Vicente Elementary School</option>
          <option value="San Isidro Elementary School">San Isidro Elementary School</option>
          <option value="San Pablo Elementary School">San Pablo Elementary School</option>
          <option value="Balingcanaway Elementary School">Balingcanaway Elementary School</option>
          <option value="San Jose Elementary School">San Jose Elementary School</option>
          <option value="San Carlos City Central School">San Carlos City Central School</option>
          <option value="Other">Other</option>
        </select>
        <input type="text" name="otherSchool" id="otherSchool" class="form-control mt-2" placeholder="Please specify other school" style="display: none;">
      </div>
      <div class="col-md-6">
        <label>Grade Level to Enroll:</label>
        <select class="form-control" name="gradelevel" id="gradelevel" required>
          <option value="">Select Grade Level</option>
          <option value="7">Grade 7</option>
          <option value="8">Grade 8</option>
          <option value="9">Grade 9</option>
          <option value="10">Grade 10</option>
          <option value="11">Grade 11</option>
          <option value="12">Grade 12</option>
        </select>
      </div>
    </div>


<div class="form-group row">
  <div class="col-md-6"> <!-- Adjust the column width as needed -->
    <div class="form-group row">
  <div class="col-md-12">
    <label>Curriculum</label>
    <input type="text" class="form-control" name="curriculum" id="curriculum" value="" readonly required style="font-size: 0.8rem;">
    </div>
</div>
  </div>
</div>

<br>
<div class="alert alert-info" role="alert">
  <strong>Uploading Documents:</strong> 
      </div>
      <div class="d-flex align-items-start">
  <!-- Picture upload section -->
  <div class="me-3">
  <label for="sf10File" class="form-label">Upload 1x1 Photo</label>
    <input type="file" class="form-control form-control-sm mb-2" name="imageFile" accept="image/png, image/jpeg"  style="width: 200px;" onchange="previewImage(event)">
    <img id="previewImage" style="width: 200px; height: 200px; object-fit: cover; border: 1px solid #ddd;" />
  </div>

  <!-- SF10 upload section -->
  <div>
    <label for="sf10File" class="form-label">Upload SF10 PDF</label>
    <input type="file" class="form-control mb-2" name="sf10File" accept="application/pdf"  onchange="previewPDF(event)">
    <embed id="previewPDF" type="application/pdf" style="width: 400px; height: 400px; object-fit: cover; border: 1px solid #ddd;" />
  </div>
</div>
  <br>
 
<br>
<div class="modal-footer d-flex justify-content-end"> <!-- Use d-flex and justify-content-end to align items to the right -->
  <button type="submit" name="submit" value="submit" class="btn btn-primary" id="add-student-btn">Submit Now</button>
</div>
                <!-- Right side: Upload 1x1 Picture -->
               
            </div>
          </div>
          
        </form>
      </div>
    </div>

    <script>
  // Image preview function
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
      const output = document.getElementById('previewImage');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }

  // PDF preview function
  function previewPDF(event) {
    const file = event.target.files[0];
    if (file.type === "application/pdf") {
      const preview = document.getElementById('previewPDF');
      const fileURL = URL.createObjectURL(file);
      preview.src = fileURL;
    }
  }
</script>


    <script>
    document.getElementById('gradelevel').addEventListener('change', function() {
        var gradeLevel = this.value;
        var curriculumSelect = document.getElementById('curriculum');
        
        // Reset the curriculum select
        curriculumSelect.selectedIndex = 0; // Reset to "Select Curriculum"
        
        // Set the curriculum based on the selected grade level
        if (gradeLevel === '7') {
            curriculumSelect.value = 'DepEd Matatag';
        } else if (gradeLevel >= '8') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '9') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '10') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '11') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '12') {
            curriculumSelect.value = 'K-12';
        }
        
        
    });
</script>
    <footer class="footer">
      <p>© 2024 Macayo Integrated School. All Rights Reserved.</p>
    </footer>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script>
    function checkLRN() {
    const lrn = document.getElementById("studentID").value;
    const lrnError = document.getElementById("lrnError");
    const submitBtn = document.getElementById("add-student-btn");

    if (lrn) {
        fetch("check_lrn.php?lrn=" + encodeURIComponent(lrn))
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    lrnError.style.display = "inline";
                    submitBtn.disabled = true; // Disable the submit button
                } else {
                    lrnError.style.display = "none";
                    submitBtn.disabled = false; // Enable the submit button
                }
            })
            .catch(error => console.error("Error:", error));
    }
}


  </script>
  <script>
    $(document).ready(function() {
      // Initialize datepicker
      $('#date_of_birth').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
      });

      // Preview image before uploading
      $('input[name="imageFile"]').on('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(event) {
            $('#previewImage').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
      });
    });

    $(document).ready(function() {
    // Existing initialization code...

    // Form submission handler
    $('form').on('submit', function(event) {
        // Show success message
        alert('Successfully submitted! Please wait for approval.');

        // Optionally, you can prevent the default action (remove this line if you want the form to submit)
        // event.preventDefault();
    });
});
    // Show/Hide other school input based on selection
    function toggleOtherSchoolInput() {
      const otherSchoolInput = document.getElementById('otherSchool');
      otherSchoolInput.style.display = document.getElementById('schoolAttended').value === 'Other' ? 'block' : 'none';
    }

    // Show/Hide other guardian input based on selection
    function toggleOtherGuardianInput() {
      const otherGuardianInput = document.getElementById('otherGuardian');
      otherGuardianInput.style.display = document.getElementById('guardianSelect').value === 'Other' ? 'block' : 'none';
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
