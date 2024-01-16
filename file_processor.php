<?php
// I Jay Patel, 000881881 certify that this material is my original work. No other person's work has been used without suitable acknowledgment and I have not made my work available to anyone else. 
/**
* @author: Jay Patel
* @version: 202335.00
* @package COMP 10260 Assignment 2
*/
// Check if the request method is POST and if a file named 'csvFile' has been set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {

    // Get the uploaded file and the specified sort column
    $file = $_FILES['csvFile'];
    $sortColumn = filter_input(INPUT_POST, 'sortColumn', FILTER_VALIDATE_INT);

    // Set a default value for sortColumn if it's invalid or missing
    if ($sortColumn === false || $sortColumn === null || $sortColumn < 1) {
        $sortColumn = 1; // Set a default value to 1
    }

    // Check if the uploaded file has no errors, is a CSV, and has content
    if ($file['error'] === UPLOAD_ERR_OK && $file['size'] > 0 && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
        
        // Open the file and get the header
        $handle = fopen($file['tmp_name'], 'r');
        $header = fgetcsv($handle);

        // Read the CSV data row by row
        $csvData = [];
        while (($data = fgetcsv($handle)) !== false) {
            $csvData[] = $data;
        }
        fclose($handle);

        $sortColumn = $sortColumn - 1;

        // Define a sorting function
        usort($csvData, function ($a, $b) use ($sortColumn) {
            $valueA = $a[$sortColumn];
            $valueB = $b[$sortColumn];
            return strnatcasecmp($valueA, $valueB);
        });

        // Combine header with sorted data
        $result = [];
        foreach ($csvData as $row) {
            $result[] = array_combine($header, $row);
        }

        // Send the JSON-encoded array as a response
        echo json_encode($result);
    } 
    else {
        // Handle invalid or missing file
        echo json_encode(['error' => 'Invalid or missing file']);
    }
} 
else {
    // Handle other HTTP methods
    echo json_encode(['error' => 'Method Not Allowed']);
}

?>
