<?php
require_once('../../config.php');

// Check if section_id is provided
if(isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];

    // Query to check if any student in the section has missing or zero grades
    $query = "SELECT COUNT(*) AS count FROM student_list sl
              INNER JOIN student_subject ss ON sl.id = ss.student_id
              INNER JOIN student_grade sg ON ss.id = sg.student_subject_id
              WHERE sl.section_id = $section_id AND (sg.grade IS NULL OR sg.grade = 0)"; // Assuming grade 0 means missing or zero grade

    $result = $conn->query($query);

    if($result) {
        $row = $result->fetch_assoc();
        $count = $row['count'];

        // Send JSON response indicating if there are students with missing grades and the count
        echo json_encode(array("has_missing_grades" => $count > 0, "missing_grades_count" => $count));
    } else {
        // Error handling if query fails
        echo json_encode(array("error" => "Query failed: " . $conn->error));
    }
} else {
    // Error handling if section_id is not provided
    echo json_encode(array("error" => "Section ID not provided."));
}
?>
