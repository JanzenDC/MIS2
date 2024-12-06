<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Application Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <style>
 body {
  background: linear-gradient(135deg, 
    rgba(0, 180, 70, 0.8), 
    rgba(0, 220, 90, 0.6), 
    rgba(255, 140, 0, 0.6), 
    rgba(0, 150, 255, 0.6), 
    rgba(200, 0, 200, 0.6) /* New additional color */
  ); 
  background-size: 400% 400%; /* This helps create a smooth animation */
  animation: gradientAnimation 15s ease infinite; /* Animation for a flowing effect */
  font-family: 'Arial', sans-serif;
  color: #333;
}

@keyframes gradientAnimation {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

    .content {
  padding: 20px;
  border-radius: 8px;
  background-color: white;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2), inset 0 1px 2px rgba(255, 255, 255, 0.5), inset 0 -1px 2px rgba(0, 0, 0, 0.2);
  margin: 20px auto; /* Center the content with auto margins */
  max-width: 900px; /* Set maximum width for the content */
  position: relative; /* For pseudo-element positioning */
}

.content::before {
  content: '';
  position: absolute;
  top: 5px;
  left: 5px;
  right: 0;
  bottom: 0;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.1);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
  z-index: -1; /* Position behind the content */
}

    .footer {
      color: black;
      text-align: center;
      padding: 10px;
      font-size: 0.9rem;
      margin-top: 20px;
    }
    .requirements {
      margin-top: 20px; /* Add some space above the requirements section */
    }
  </style>
</head>
<body>
  
  <header class="main-header">
   
  </header>

  <div class="container">
    <div class="content">
      <img id="sidebar-logo" src="dist/img/macayo_logo.png" alt="DepEd Logo" style="max-width: 100px; margin-left: auto; margin-right: auto; display: block;">
      
      <br>
      <img id="sidebar-logo" src="dist/img/macayo_title.png" alt="DepEd Logo" style="max-width: 550px; margin-left: auto; margin-right: auto; display: block;">
      <h3 class="mb-4 text-center">Admission Portal</h3>
      
        
<!-- Bootstrap Buttons in One Line -->
<!-- Enhanced Bootstrap Buttons with Bigger Requirements Button -->
<div class="container mt-3 text-center">
  <div class="btn-group" role="group">
    <!-- Bigger Requirements Button -->
    <a href="admission_portal.php" class="btn btn-info btn-lg rounded-pill shadow-lg mx-2 btn-effect">Requirements</a>
    
    <!-- Other Buttons -->
    <a href="admission_status.php" class="btn btn-success btn-lg rounded-pill shadow-lg mx-2 btn-effect">Application Status</a>
    <a href="admission_contactus.php" class="btn btn-info btn-lg rounded-pill shadow-lg mx-2 btn-effect">Contact Us</a>
  </div>
</div>

<!-- Add this Bootstrap CDN if it's not already included in your project -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS for Hover and Click Effects -->
<style>
  /* Button hover effect with shadow */
  .btn-effect {
    transition: all 0.3s ease-in-out;
  }

  .btn-effect:hover {
    transform: scale(1.1); /* Grow effect on hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Larger shadow on hover */
  }

  /* Button click effect */
  .btn-effect:active {
    transform: scale(1.15); /* Bigger on click */
    transition: all 0.1s ease-in-out;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Even larger shadow on click */
  }

  /* Make Requirements Button Bigger */
  .btn-bigger {
    font-size: 1.5rem; /* Larger text */
    padding: 15px 40px; /* Bigger padding */
  }
</style>


<!-- Add this Bootstrap CDN if it's not already included in your project -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<br><br>
      <div class="alert alert-info" role="alert">
      <strong>Verify</strong> your status.
      </div>

      <!-- New requirements section -->
      <div class="card requirements">
  <div class="card-body">
    <h6>S.Y. 2024 - 2025 Admission Form</h6>
    
    <form action="submit_admission.php" method="POST" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label for="lrn" class="form-label">LRN No.</label>
        <input type="text" class="form-control" id="lrn" name="lrn" required>
      </div>
      
      <div class="col-md-4">
        <label for="lastName" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" required>
      </div>

      <div class="col-md-4 d-flex justify-content-center">
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-sm py-2 px-3 custom-width">Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- Add this Bootstrap CDN if it's not already included in your project -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .custom-width {
        width: 250px; /* Adjust the width as needed */
    }
</style>


    </div>
  </div>

  <footer class="footer">
    <p>© 2024 Macayo Integrated School. All Rights Reserved.</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  
</body>
</html>
