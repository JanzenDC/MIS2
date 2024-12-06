<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=0.8"> <!-- Adjusted scale for smaller layout -->
  <title>Student Online Services</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f8ff; /* Light background for better contrast */
      font-family: 'Arial', sans-serif; /* Updated font family */
      line-height: 1.6; /* Increased line height for readability */
      color: #333; /* Darker text for better readability */
      position: relative; /* For watermark positioning */
      overflow: hidden; /* Prevents scroll bars */
    }

    /* Watermark Logo */
    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.1; /* Light transparency for watermark effect */
      pointer-events: none; /* Allows clicks to pass through */
      z-index: -1; /* Behind other elements */
    }

    /* Header */
    .main-header {
      background-color: #004d00; /* Darker green */
      color: white;
      text-align: center;
      padding: 10px; /* Reduced padding */
      font-size: 20px; /* Slightly smaller font size */
      font-weight: bold;
    }

    /* Sidebar Navigation */
    .sidebar {
      background-color: #f0f0f0; /* Light Gray */
      padding: 15px; /* Reduced padding */
      height: auto; /* Allow height to adjust based on content */
      border-right: 2px solid #dee2e6; /* Light gray border */
    }

    .sidebar h3 {
      color: #ffffff; /* White text color */
      font-weight: bold;
      font-size: 1.3rem; /* Decreased font size */
      text-transform: uppercase; /* Uppercase text */
      text-align: center; /* Centered text */
      background-color: #008000; /* Matching green background */
      padding: 10px 0; /* Reduced padding for spacing */
      border-radius: 5px; /* Rounded corners */
      letter-spacing: 1px; /* Increased letter spacing */
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Subtle shadow for depth */
    }

    /* Main Content */
    .content {
      padding: 15px; /* Reduced padding */
    }

    .program-box {
      background-color: #004d00; /* Darker green */
      color: white;
      padding: 20px; /* Reduced padding */
      text-align: center;
      margin: 15px 0; /* Reduced margin for spacing */
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Enhanced shadow for depth */
    }

    .program-box h4 {
      font-weight: bold;
      font-size: 1.5rem; /* Smaller font size for the heading */
    }

    .apply-btn {
      background-color: #ffffff; /* White */
      color: #004d00; /* Darker green */
      padding: 8px 16px; /* Reduced padding */
      text-decoration: none;
      font-weight: bold;
      border: 2px solid #004d00; /* Darker green */
      border-radius: 5px;
      transition: background-color 0.3s, color 0.3s; /* Smooth transition */
      margin-top: 10px; /* Space above button */
      display: inline-block; /* Ensures proper spacing */
    }

    .apply-btn:hover {
      background-color: #004d00; /* Darker green on hover */
      color: white;
      border: 2px solid #003700; /* Darker shade on hover */
    }

    /* Card Styles */
    .card {
      border: none; /* No border */
      border-radius: 5px; /* Rounded corners */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    }

    .card-header {
      background-color: #28a745; /* Bootstrap success color */
      border-radius: 5px 5px 0 0; /* Rounded top corners */
    }

    .card-body {
      font-size: 0.9rem; /* Slightly smaller font size for card */
    }

    /* Footer */
    .footer {
      color: black;
      text-align: center;
      padding: 10px;
      font-size: 0.9rem;
      margin-top: 20px;
    }

    .note {
      background-color: #FFEECC; /* Light background for the note */
      padding: 8px; /* Reduced padding */
      margin-top: 15px; /* Reduced margin */
      border-left: 5px solid #FFC107; /* Left border for emphasis */
    }
  </style>
</head>
<body>

  <!-- Watermark Logo -->
  <img src="dist/img/deped_logo.png" alt="DepEd Logo" class="watermark" style="max-width: 700px; margin-left: 200px; transition: all 0.9s ease;">

  <!-- Header -->
  <header class="main-header">
    <img id="sidebar-logo" src="dist/img/deped_logo.png" alt="DepEd Logo" style="max-width: 40px; margin-left: 5px; transition: all 0.9s ease;">
    MACAYO INTEGRATED SCHOOL
    <img id="sidebar-logo" src="dist/img/macayo_logo.png" alt="DepEd Logo" style="max-width: 40px; margin-left: 5px; transition: all 0.9s ease;">
  </header>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      </div>

      <!-- Main Content -->
      <div class="col-md-9 content">
      <div class="row justify-content-end"> <!-- Adjusted to align content to the right -->
  <div class="col-md-8"> <!-- Removed right margin utility -->
    <div class="program-box text-center"> <!-- Centered text within the box -->
      <h4>Apply Now!</h4>
      <p>Applications are accepted from students at all grade levels.</p>
      <a href="admission_form.php" class="apply-btn">Go to Application Form</a>
    </div>
  </div>

  <div class="container mt-3">
    <div class="card">
      
      <div class="card-header text-white text-center"> <!-- Centered header text -->
        <h5 class="mb-0">S.Y. 2024 - 2025 Admission Requirements</h5>
      </div>
      <div class="card-body text-center"> <!-- Centered body text -->
        <h6 class="text-danger">Deadline of Application - May 17, 2024</h6>
        <p>Please ensure that you have a soft copy of the following requirements to be uploaded before proceeding:</p>
        <ul class="list-unstyled"> <!-- Clean bullet points -->
          <li>2x2 picture in white background</li>
          <li>Clear copy of grades:</li>
          <ul>
            <li>Grade 6 SF10 for graduating Grade 6</li>
            <li>Certification of Grades or Transcript of Records for Transferees</li>
          </ul>
        </ul>
        <p><strong>Important:</strong> Only ONE application can be filed for the school. Multiple submissions of applications can be grounds for disqualification.</p>
        <div class="note">
          <p><strong>Note:</strong> Some academic programs may have other officially approved requirements prior to admission. If you wish to change any part of the application, contact the school where the application is submitted.</p>
        </div>
      </div>
    </div>
  </div>
</div>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
      <p>© 2024 Macayo Integrated School. All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
