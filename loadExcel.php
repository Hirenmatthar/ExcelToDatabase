<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$excelFile = 'demo.xlsx';
$spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($excelFile);
$worksheet = $spreadsheet->getActiveSheet();
$data = $worksheet->toArray(null, true, true, true);

// Set a flag to skip the first row
$skipFirstRow = true;

foreach ($data as $row) {
    if ($skipFirstRow) {
        $skipFirstRow = false;
        continue; // Skip the first row
    }
    
    $column1 = $row['A']; 
    $column2 = $row['B'];
    $column3 = $row['C'];
    
    // Prepare the INSERT statement
    $stmt = $conn->prepare("INSERT INTO students (name, age, sex) VALUES (?, ?, ?)");

    // Bind the values
    $stmt->bindParam(1, $column1);
    $stmt->bindParam(2, $column2);
    $stmt->bindParam(3, $column3);

    // Execute the query
    if ($stmt->execute()) {
        echo "Row inserted successfully!<br>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2] . "<br>";
    }
}

$conn = null;
?>
