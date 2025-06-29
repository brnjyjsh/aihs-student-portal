<?php

// Fetch the active school year if not editing an existing teacher
$activeSchoolYearId = null;
if (!isset($_GET['id'])) {
    $activeSchoolYearQuery = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");
    $activeSchoolYear = $activeSchoolYearQuery->fetch_assoc();
    $activeSchoolYearId = isset($activeSchoolYear['id']) ? $activeSchoolYear['id'] : null;
}

// Add a default value for 'type' if not present
if (!isset($type)) {
    $type = 2; // Default value for 'type'
}

// Function to import CSV data into the database
function importCSV($filename, $type)
{
    // Database connection
    $conn = new mysqli("localhost", "root", "", "sis_db");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize variables to track skipped rows
    $skippedDueToIncompleteData = 0;

    // Open the CSV file for reading
    if (($handle = fopen($filename, "r")) !== FALSE) {
        // Read the CSV headers
        $headers = fgetcsv($handle);

        // Loop through each row in the CSV file
        while (($data = fgetcsv($handle)) !== FALSE) {
            // Check if any required field is empty
            if (empty($data[0]) || empty($data[1]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($data[6]) || empty($data[7]) || empty($data[8]) || empty($data[9])) {
                // Increment counter for incomplete data
                $skippedDueToIncompleteData++;
                continue;
            }

            // Prepare the data to be inserted into the database
            $roll = $conn->real_escape_string($data[0]);
            $firstname = $conn->real_escape_string($data[1]);
            $middlename = isset($data[2]) ? $conn->real_escape_string($data[2]) : ''; // Optional field
            $lastname = $conn->real_escape_string($data[3]);
            $dob = date('Y-d-m', strtotime($data[4]));
            $username = $conn->real_escape_string($data[5]);
            $password = md5($conn->real_escape_string($data[6]));
            $gender = $conn->real_escape_string($data[7]);
            $contact = $conn->real_escape_string($data[8]);
            $email_address = $conn->real_escape_string($data[9]);

            // Insert the data into the database
            $sql = "INSERT INTO teacher_list (roll, firstname, middlename, lastname, dob, type, username, password, gender, contact, email_address) 
                    VALUES ('$roll', '$firstname', '$middlename', '$lastname', '$dob', '$type', '$username', '$password', '$gender', '$contact', '$email_address')";
            $conn->query($sql);
        }

        // Close the CSV file
        fclose($handle);
    }

    // Close the database connection
    $conn->close();

    // Return number of skipped rows due to incomplete data
    return $skippedDueToIncompleteData;
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Handle the file upload
    if ($_FILES['file']['error'] == 0) {
        $filename = $_FILES['file']['tmp_name'];
        // Call the importCSV function
        $skippedRows = importCSV($filename, $type);
        $message = array('type' => 'success', 'text' => 'CSV imported successfully!');

        // Add message for skipped rows due to incomplete data
        if ($skippedRows > 0) {
            $message['text'] .= ' '.$skippedRows.' row(s) skipped due to incomplete data.';
        }
    } else {
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
            <h3 class="card-title"><b><?= isset($id) ? "New Teacher Details - ". $roll : "Import CSV Teacher Data" ?></b></h3>
            <div class="card-tools">
                <a href="./?page=teachers" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
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
</body>
</html>
