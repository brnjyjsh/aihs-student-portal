<?php
$userID = $_settings->userdata('id');
$userQuery = $conn->prepare("SELECT s.*, d.name as strand, c.name as section, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname FROM student_list s INNER JOIN strand_list d ON s.strand_id = d.id INNER JOIN section_list c ON s.section_id = c.id WHERE s.id = ?");
$userQuery->bind_param("i", $userID);
$userQuery->execute();

if ($userQuery) {
    $userData = $userQuery->get_result()->fetch_assoc();

    if ($userData) {
        foreach ($userData as $k => $v) {
            $$k = $v;
        }
    } else {
        // Handle the case when no user data is found
    }
} else {
    // Handle the case when there's an issue with the database query
}
?>

<style>
th {
    color: #dddddd;
    background-color: #001F3F;
}
</style>

<div class="content py-2">
    <div class="col-12">
        <div class="card card-outline card-navy shadow rounded-0">
            <div class="card-body rounded-0">
                <h2>Enrolled Subjects</h2>
                <div class="row">
                    <div class="col-md-6">
                        <h5>
                            <?php
                            require_once('./config.php'); // Include your database connection and configuration file

                            // Query to get the current school year with status = 1
                            $querySchoolYear = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");

                            if ($querySchoolYear->num_rows > 0) {
                                $schoolYearData = $querySchoolYear->fetch_assoc();
                                $activeSchoolYearID = $schoolYearData['id']; // Get the active school year ID
                                $activeSchoolYearName = $schoolYearData['name'];
                                echo "School Year: $activeSchoolYearName<br>";
                            } else {
                                echo "No current school year with status = 1 found.";
                                exit; // Exit if there's no active school year
                            }

                            
                            
                            ?>
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <h5 style="float: right;">
                            <!-- Conditionally display "First Semester" or "Second Semester" based on the active quarter -->
                            <?php

                            // Query to get the current quarter with status = 1
                            $queryQuarter = $conn->query("SELECT * FROM `quarter_list` WHERE status = 1");

                            if ($queryQuarter->num_rows > 0) {
                                $quarterData = $queryQuarter->fetch_assoc();
                                $activeQuarterID = $quarterData['id']; // Get the active quarter ID
                                $activeQuarterName = $quarterData['name'];
                                echo "$activeQuarterName | ";
                            } else {
                                echo "No current quarter with status = 1 found.";
                                exit; // Exit if there's no active quarter
                            }

                            if ($activeQuarterName == "First Quarter" || $activeQuarterName == "Second Quarter") {
                                echo "First Semester";
                            } elseif ($activeQuarterName == "Third Quarter" || $activeQuarterName == "Fourth Quarter") {
                                echo "Second Semester";
                            }
                            ?>
                        </h5>
                    </div>
                </div>
                <div class="row no-gutters">
                    <div class="container-fluid">
                        <div class="container-fluid table-responsive">
                            <table class="table table-bordered table-striped" id="academic-history">
                                <thead style="background-color: black">
                                    <tr class="bg-gradient-dark">
                                        <th class="py-1 text-center">Subject Code</th>
                                        <th class="py-1 text-center">Subject</th>
                                        <th class="py-1 text-center">Type</th>
                                        <th class="py-1 text-center">Quarter</th>   
                                        <th class="py-1 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $academics = $conn->query("SELECT a.*, c.name AS subject, c.subject_code, st.name AS subject_type_name, g.grade
                                    FROM student_subject a
                                    INNER JOIN subject_list c ON a.subject_id = c.id
                                    INNER JOIN quarter_list f ON a.quarter_id = f.id
                                    INNER JOIN subject_type st ON c.subject_type_id = (
                                        SELECT id
                                        FROM subject_type
                                        WHERE name = st.name
                                    )
                                    LEFT JOIN student_grade g ON a.id = g.student_subject_id
                                    WHERE a.student_id = '{$id}' AND a.school_year_id = '{$activeSchoolYearID}' AND a.quarter_id = '{$activeQuarterID}'
                                    ORDER BY CASE WHEN g.grade IS NULL THEN 1 ELSE 0 END, g.grade DESC");
                            
                            if ($academics->num_rows > 0) {
                                while ($row = $academics->fetch_assoc()):
                                    // ... (your existing table rows) ...
                                    ?>
                                    <tr>
                                        <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject_code'] ?></span></td>
                                        <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject'] ?></span></td>
                                        <td class="px-2 py-1 align-middle text-center"><p class="m-0 truncate-1"><?php echo $row['subject_type_name'] ?></p></td>
                                        <td class="px-2 py-1 align-middle text-center" data-quarter-id="<?= $row['quarter_id'] ?>"><span class=""><?= $activeQuarterName ?></span></td>
                                        <td class="align-middle text-center">
                                            <?php 
                                            if ($row['grade'] === null || $row['grade'] == 0) {
                                                echo '<span class="rounded-pill badge badge-dark bg-gradient-dark px-3">Not Graded</span>';
                                            } else {
                                                echo '<span class="rounded-pill badge badge-success bg-gradient-yellow px-3">Graded</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php endwhile;
                            } else {
                                // Display a message when no enrolled subjects are found
                                echo '<tr><td colspan="6" class="text-center">No enrolled subjects found for current School Year / Quarter.</td></tr>';
                            } ?>         
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
