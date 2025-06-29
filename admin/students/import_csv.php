<?php

// Fetch the active school year if not editing an existing student
$activeSchoolYearId = null;
if (!isset($_GET['id'])) {
    $activeSchoolYearQuery = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");
    $activeSchoolYear = $activeSchoolYearQuery->fetch_assoc();
    $activeSchoolYearId = isset($activeSchoolYear['id']) ? $activeSchoolYear['id'] : null;
}

// Add a default value for 'type' if not present
if (!isset($type)) {
    $type = 3; // Default value for 'type'
}

// Function to import CSV data into the database
function importCSV($filename, $schoolYearId, $type)
{
    // Database connection
    $conn = new mysqli("localhost", "root", "", "sis_db");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize variables to track skipped rows
    $skippedDueToDuplicate = 0;
    $skippedDueToIncompleteData = 0;

    // Open the CSV file for reading
    if (($handle = fopen($filename, "r")) !== FALSE) {
        // Read the CSV headers
        $headers = fgetcsv($handle);

        // Loop through each row in the CSV file
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Check if any required field is empty
            if (empty($data[0]) || empty($data[1]) || empty($data[3]) || empty($data[4]) || empty($data[6]) || empty($data[8]) || empty($data[10]) || empty($data[11]) || empty($data[12]) || empty($data[13]) || empty($data[14]) || empty($data[15])) {
                // Increment counter for incomplete data
                $skippedDueToIncompleteData++;
                continue;
            }

            // Prepare the data to be inserted into the database
            $roll = $conn->real_escape_string($data[0]);

            // Check if the roll already exists in the database
            $check_query = "SELECT COUNT(*) AS num_rows FROM student_list WHERE roll = '$roll'";
            $result = $conn->query($check_query);
            $row = $result->fetch_assoc();

            if ($row['num_rows'] > 0) {
                // Increment counter for duplicate rolls
                $skippedDueToDuplicate++;
                continue;
            }

            // Prepare other fields for insertion
            $firstname = $conn->real_escape_string($data[1]);
            $middlename = isset($data[2]) ? $conn->real_escape_string($data[2]) : ''; // Optional field
            $lastname = $conn->real_escape_string($data[3]);
            $dob = date('Y-d-m', strtotime($data[4]));
            $strand_id = $conn->real_escape_string($data[6]);
            $section_id = $conn->real_escape_string($data[8]);
            $student_status_id = $conn->real_escape_string($data[10]);
            $username = $conn->real_escape_string($data[11]);
            $password = md5($conn->real_escape_string($data[12]));
            $gender = $conn->real_escape_string($data[13]);
            $contact = $conn->real_escape_string($data[14]);
            $email_address = $conn->real_escape_string($data[15]);
            $guardian_name = isset($data[16]) ? $conn->real_escape_string($data[16]) : ''; // Optional field
            $guardian_contact = isset($data[17]) ? $conn->real_escape_string($data[17]) : ''; // Optional field

            // Insert the data into the database
            $sql = "INSERT INTO student_list (roll, firstname, middlename, lastname, dob, strand_id, section_id, school_year_id, student_status_id, type, username, password, gender, contact, email_address, guardian_name, guardian_contact) 
                    VALUES ('$roll', '$firstname', '$middlename', '$lastname', '$dob', '$strand_id', '$section_id', '$schoolYearId', '$student_status_id', '$type', '$username', '$password', '$gender', '$contact', '$email_address', '$guardian_name', '$guardian_contact')";
            $conn->query($sql);
        }

        // Close the CSV file
        fclose($handle);
    }

    // Close the database connection
    $conn->close();

    // Return number of skipped rows due to duplicate rolls and incomplete data
    return array('duplicate' => $skippedDueToDuplicate, 'incomplete' => $skippedDueToIncompleteData);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Handle the file upload
    if ($_FILES['file']['error'] == 0) {
        $filename = $_FILES['file']['tmp_name'];
        // Call the importCSV function with the active school year ID
        $skippedRows = importCSV($filename, $activeSchoolYearId, $type);
        
        // Set the success message
        $message = array('type' => 'success', 'text' => 'CSV imported successfully!');
        
        // Add messages for skipped rows
        if ($skippedRows['duplicate'] > 0) {
            $message['text'] .= ' '.$skippedRows['duplicate'].' row(s) skipped due to duplicate LRN(s).';
        }
        if ($skippedRows['incomplete'] > 0) {
            $message['text'] .= ' '.$skippedRows['incomplete'].' row(s) skipped due to incomplete data.';
        }
    } else {
        // Set the error message for file upload failure
        $message = array('type' => 'error', 'text' => 'Error uploading file.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Import Form</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        input[type="file"] {
            display: block;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<div class="content py-2">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h3 class="card-title"><b><?= isset($id) ? "Update Student Details - ". $roll : "Import CSV Student Data" ?></b></h3>
            <div class="card-tools">
                <a href="./?page=students" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
    <div class="container">
        <h2>Import CSV Data</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file" accept=".csv">
            <input type="submit" name="submit" value="Import CSV">
        </form>
        
        <?php
        // Display success or error messages
        if (isset($message)) {
            printf('<div class="message %s">%s</div>', $message['type'], $message['text']);
        }
        ?>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</body>
</html>
