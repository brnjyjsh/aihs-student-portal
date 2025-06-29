<?php
$userID = $_settings->userdata('id');
$userQuery = $conn->prepare("SELECT s.*, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname FROM teacher_list s WHERE s.id = ?");
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
                <h2>Assigned Subjects</h2>
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
                            // Query to get the current quarter and corresponding semester with status = 1
                            $query = $conn->query("SELECT q.name AS quarter_name, s.name AS semester_name 
                                                FROM `quarter_list` q 
                                                INNER JOIN `semester_list` s ON 
                                                (q.name IN ('First Quarter', 'Second Quarter') AND s.name = 'First Semester') OR 
                                                (q.name IN ('Third Quarter', 'Fourth Quarter') AND s.name = 'Second Semester') 
                                                WHERE q.status = 1 AND s.status = 1");

                            if ($query->num_rows > 0) {
                                $data = $query->fetch_assoc();
                                $activeQuarterName = $data['quarter_name'];
                                $activeSemesterName = $data['semester_name'];
                                echo $activeQuarterName  . ' | ' . $activeSemesterName;
                            } else {
                                echo "No current quarter or semester with status = 1 found.";
                                exit; // Exit if there's no active quarter or semester
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
                                        <th class="py-1 text-center">Quarter</th>
                                        <th class="py-1 text-center">Strand & Track</th>   
                                        <th class="py-1 text-center">Section</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $academics = $conn->query("SELECT a.*, c.name AS subject, c.subject_code, d.name AS strand, e.name AS section, f.name AS quarter, g.name AS school_year, e.strand_id AS section_strand_id FROM `trsubject` a 
                                INNER JOIN subject_list c ON a.subject_id = c.id 
                                INNER JOIN strand_list d ON c.strand_id = d.id 
                                INNER JOIN section_list e ON a.section_id = e.id
                                INNER JOIN quarter_list f ON a.quarter_id = f.id
                                INNER JOIN school_year_list g ON a.school_year_id = g.id
                                WHERE teacher_id = '{$id}' 
                                AND a.school_year_id = '{$activeSchoolYearID}' 
                                AND f.name = '{$activeQuarterName}' ");

                                if ($academics->num_rows > 0) {
                                    while ($row = $academics->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject_code'] ?></span></td>
                                    <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject'] ?></span></td>
                                    <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['quarter'] ?></span></td>
                                    <td class="px-2 py-1 align-middle text-center">
                                        <?php 
                                        if (isset($row['section_strand_id'])) {
                                            $strand_id = $row['section_strand_id'];
                                            // Retrieve the strand name from the strand_list table
                                            $strand_info = $conn->query("SELECT name FROM `strand_list` WHERE id = $strand_id");

                                            $strand_data = $strand_info->fetch_assoc();
                                            if ($strand_data) {
                                                echo $strand_data['name'];
                                            } else {
                                                echo 'N/A';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td class="align-middle text-center"><p class="m-0 truncate-1">
                                        <?php 
                                        if (isset($row['section_id'])) {
                                            $section_id = $row['section_id'];
                                            // Retrieve the section and grade level names from the section_list and grade_level_list tables
                                            $section_info = $conn->query("SELECT s.name AS section_name, g.name AS grade_level_name 
                                                                        FROM `section_list` s 
                                                                        JOIN `grade_level_list` g ON s.grade_level_id = g.id 
                                                                        WHERE s.id = $section_id");

                                            $section_data = $section_info->fetch_assoc();
                                            if ($section_data) {
                                                echo $section_data['grade_level_name'] . ' - ' . $section_data['section_name'];
                                            } else {
                                                echo 'N/A';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </p></td>
                                </tr>
                                <?php endwhile; ?>
                                <?php
                                } else {
                                    // Display message when there are no assigned subjects
                                    echo '<tr><td colspan="5" class="text-center">No Assigned Subject for this Quarter</td></tr>';
                                }
                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
