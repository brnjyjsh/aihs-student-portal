<?php
// Your database connection and configuration

if(isset($_GET['section_id'])) {
    $sectionId = $_GET['section_id'];

    $students = $conn->query("SELECT s.id, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as name 
        FROM student_list s
        JOIN trsubject t ON s.id = t.student_id 
        WHERE t.section_id = $sectionId AND s.delete_flag = 0 AND s.status = 1");

    $studentData = array();

    while ($row = $students->fetch_assoc()) {
        $studentData[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($studentData);
    exit;
}
