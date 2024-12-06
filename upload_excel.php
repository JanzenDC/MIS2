<?php
require 'vendor/autoload.php'; // Ensure this is the correct path

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['submit'])) {
    $file = $_FILES['excelFile'];

    // Check if the file was uploaded without errors
    if ($file['error'] === 0) {
        $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file['tmp_name']);
        $reader = IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($file['tmp_name']);
        
        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();
        
        // Fetch data from columns A to E
        $data = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // This will include empty cells
            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            // Only take columns A to E (0 to 4 in the array)
            if (!empty($rowData[0])) { // Check if there's an LRN No to avoid empty rows
                $data[] = array_slice($rowData, 0, 5);
            }
        }
        
        // Store data in session to access it in the main page
        session_start();
        $_SESSION['students'] = $data;
        
        // Redirect back to the enrollment page
        header("Location: enroll-student.php");
        exit();
    } else {
        echo "Error uploading file.";
    }
}
?>
