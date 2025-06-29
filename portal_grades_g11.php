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
<?php
                                            require_once('./config.php'); // Include your database connection and configuration file

                                            // Query to get the current school year with status = 1
                                            $querySchoolYear = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");

                                            if ($querySchoolYear->num_rows > 0) {
                                                $schoolYearData = $querySchoolYear->fetch_assoc();
                                                $activeSchoolYearID = $schoolYearData['id']; // Get the active school year ID
                                                $activeSchoolYearName = $schoolYearData['name'];
                                            } else {
                                                exit; // Exit if there's no active school year
                                            }
                                            ?>
                                           
                                            <!-- Conditionally display "First Semester" or "Second Semester" based on the active quarter -->
                                            <?php

                                            // Query to get the current quarter with status = 1
                                            $queryQuarter = $conn->query("SELECT * FROM `quarter_list` WHERE status = 1");

                                            if ($queryQuarter->num_rows > 0) {
                                                $quarterData = $queryQuarter->fetch_assoc();
                                                $activeQuarterID = $quarterData['id']; // Get the active quarter ID
                                                $activeQuarterName = $quarterData['name'];
                                            } else {
                                                exit; // Exit if there's no active quarter
                                            }
                                            ?>


<style>
    th {
        color: #dddddd;
        background-color: #FFC107;
        color: black;
    }

    /* Define styles for the accordion */
    .accordion {
        display: flex;
        flex-direction: column;
    }

    .accordion-header {
        cursor: pointer;
        padding: 10px;
        background-color: #001F3F;
        color: #dddddd;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 5px;
        text-align: center;
    }

    .accordion-body {
        display: none;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin: 5px, 5px, 5px, 5px;
        background-color: #f5f5f5;
    }
    .unselectable {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
 #button {
     line-height: 12px;
     width: 18px;
     font-size: 8pt;
     font-family: tahoma;
     margin-top: 1px;
     margin-right: 2px;
     position:absolute;
     top:0;
     right:0;
 }
</style>

<div class="content py-2" >
    <div class="col-12">
        <div class="card card-outline card-navy shadow rounded-0" >
            <div class="card-body rounded-0">
            <?php if ($userData) : ?>
                <div style="float: right;">
            <button class="btn btn-sm btn-success bg-success btn-flat" type="button" id="print"><i class="fa fa-print":></i> Print</button>
                <?php endif; ?>
            </div>
            <h2>My Grades - Grade 11 <?= $userData['section'] ?> </h2>
            
            <div class="accordion" id="outprint">
                <?php
                // Query to get the unique quarters and school years for the user in Grade 11
                $quarterQuery = $conn->query("SELECT DISTINCT f.id AS quarter_id, f.name AS quarter, a.school_year_id, s.name AS school_year_name
                FROM student_subject a
                INNER JOIN quarter_list f ON a.quarter_id = f.id
                LEFT JOIN school_year_list s ON a.school_year_id = s.id
                INNER JOIN section_list sec ON a.section_id = sec.id
                WHERE a.student_id = '{$id}' AND sec.grade_level_id = 1");

                if (!$quarterQuery) {
                    echo "Error: " . $conn->error;
                }

                if ($quarterQuery->num_rows > 0) {
                    while ($quarterData = $quarterQuery->fetch_assoc()) {
                        $quarterName = $quarterData['quarter'];
                        $schoolYearId = $quarterData['school_year_id'];
                        $schoolYearName = $quarterData['school_year_name'];
                        ?>
                        <div class="accordion-header unselectable" onclick="toggleAccordion(this)">
                            <?= "{$schoolYearName} | {$quarterName} " ?>
                        </div>
                        <div class="accordion-body table-responsive">
                        <table class="table table-bordered table-striped table-bordered">
                        <colgroup>
                            <col width="5%">
                            <col width="20%">
                            <col width="5%">
                            <col width="1%">
                            <col width="2%">
                        </colgroup>
                            <thead style="background-color: black" id="outprint">
                                <tr class="bg-gradient-dark">
                                    <th class="py-1 align-middle text-center">Subject Code</th>
                                    <th class="py-1 align-middle text-center">Subject</th>
                                    <th class="py-1 align-middle text-center">Type</th>
                                    <th class="py-1 align-middle text-center">Grade</th>
                                    <th class="py-1 align-middle text-center">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalGrades = 0; // Initialize the total grades
                                $totalSubjects = 0; // Initialize the count of subjects with grades

                                // Query to get subjects for the specific quarter
                                $subjectQuery = $conn->query("SELECT a.*, c.name AS subject, c.subject_code, f.name AS quarter, g.grade AS student_grade, st.name AS subject_type_name
                                FROM student_subject a
                                INNER JOIN subject_list c ON a.subject_id = c.id
                                INNER JOIN quarter_list f ON a.quarter_id = f.id
                                INNER JOIN section_list sec ON a.section_id = sec.id
                                LEFT JOIN (
                                    SELECT student_subject_id, grade
                                    FROM student_grade
                                ) g ON a.id = g.student_subject_id
                                LEFT JOIN subject_type st ON c.subject_type_id = (
                                    SELECT id
                                    FROM subject_type
                                    WHERE name = st.name
                                )
                                WHERE a.student_id = '{$id}' 
                                AND f.name = '{$quarterName}'
                                AND sec.grade_level_id = 1");

                                while ($row = $subjectQuery->fetch_assoc()):
                                    if ($row['student_grade'] !== null && $row['student_grade'] != 0) {
                                        $totalGrades += $row['student_grade']; // Accumulate the grades
                                        $totalSubjects++;
                                        ?>
                                        <tr>
                                            <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject_code'] ?></span></td>
                                            <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject'] ?></span></td>
                                            <td class="px-2 py-1 align-middle text-center"><p class="m-0 truncate-1"><?php echo $row['subject_type_name'] ?></p></td>
                                            <td class="px-2 py-1 align-middle text-center"><?= $row['student_grade'] ?></td>
                                            <td class="px-2 py-1 align-middle text-center">
                                                <?php
                                                $grade = $row['student_grade'];
                                                if ($grade !== null && $grade != 0) {
                                                    echo ($grade >= 75) ? '<a style="color: green;">Passed</a>' : '<a style="color: red;">Failed</a>';
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php }
                                endwhile;

                                if ($totalSubjects > 0) {
                                    $gpa = round($totalGrades / $totalSubjects, 2); // Calculate the GPA and round off to 2 decimal places
                                    echo "<tr><td colspan='3' class='table-striped text-end text-right'>GPA:</td><td class='align-middle text-center' ><b >$gpa</b></td></td><td></tr>";
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No subjects with grades for this quarter</td></tr>';
                                }
                                ?>
                            </tbody>
                            </table>
                            </div>
                            <?php
                        }
                    } else {
                        // Display message when there are no quarters found for Grade 11
                        echo '<div class="text-center">No enrolled subjects found for Grade 11.</div>';
                    }
                    ?>
                </div>
            </div>
        </div> 
    </div>
</div>
<noscript id="print-header">
    <div class="row">
        <div class="col-2 d-flex justify-content-center align-items-center">
            <img src="<?= validate_image($_settings->info('logo')) ?>" class="img" id="sys_logo" alt="System Logo" style="position:absolute; left:80px; top:20px; height:100px; width:auto; max-width:300px;">
        </div>
        <div class="col-8">
            <h4 class="text-center">Republic of the Philippines</h4>
            <h4 class="text-center"><b><?= $_settings->info('name') ?></b></h4>
            <h4 class="text-center">Amadeo, Cavite</h4>
            <br>
            <h3 class="text-center"><b>My Grades - Grade 11 <?= $userData['section'] ?></b></h3>
            <br>
            <br>
            <div class="container-fluid" >
                <style>
                    #sys_logo{
                        width:5em;
                        height:5em;
                        object-fit:scale-down;
                        object-position:center center;
                    }
                    /* Define custom styles for the smaller modal */
                    .custom-modal {
                        width: 350px !important; /* Adjust the width as needed */
                        height: 250px !important; /* Adjust the height as needed */
                    }

                </style>
                <div class="row">  
                <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Name:</label> <?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
                    <!--<div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Grade/Section:</label> <?php 
                            if (isset($section_id)) {
                                // Retrieve the section details from the section_list table
                                $section = $conn->query("SELECT name, grade_level_id FROM `section_list` WHERE id = $section_id");
                                $section_data = $section->fetch_assoc();
                                $section_name = $section_data['name'];
                                
                                // Get the grade level details from the grade_level_list table
                                $grade_level_id = $section_data['grade_level_id'];
                                $grade_level = $conn->query("SELECT name FROM `grade_level_list` WHERE id = $grade_level_id");
                                $grade_level_name = $grade_level->fetch_assoc()['name'];
                                
                                // Display the Grade Year Level and Section
                                echo "$grade_level_name - $section_name";
                            } else {
                                echo 'N/A';
                            }
                            ?>
                                <div class="pl-4"></div>
                            </div>
                    </div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Strand/Track:</label>
                            <?php 
                            if (isset($strand_id)) {
                                // Retrieve the strand name from the strand_list table
                                $strand = $conn->query("SELECT name FROM `strand_list` WHERE id = $strand_id");
                                $strand_name = $strand->fetch_assoc()['name'];
                                echo $strand_name;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                    </div>
                </div>
        </div>
        <div class="col-2"></div>
    </div>

</noscript>

<script>
function toggleAccordion(element) {
    // Toggle visibility of the accordion body when the header is clicked
    const body = element.nextElementSibling;

    // Check if the body is not visible or has display:none
    if (window.getComputedStyle(body).display === 'none') {
        body.style.display = 'block';
    } else {
        body.style.display = 'none';
    }
}

$('#print').click(function(){
            start_loader()
            $('#academic-history').dataTable().fnDestroy()
            var _h = $('head').clone()
            var _p = $('#outprint').clone()
            var _ph = $($('noscript#print-header').html()).clone()
            var _el = $('<div>')
            _p.find('tr.bg-gradient-dark').removeClass('bg-gradient-dark')
            _p.find('tr>td:last-child,tr>th:last-child,colgroup>col:last-child').remove()
            _p.find('.badge').css({'border':'unset'})
            _el.append(_h)
            _el.append(_ph)
            _el.find('title').text('My Grades - Print View')
            _el.append(_p)

            // Append "Printed by ICT Coordinator" text at the bottom right of the printed page
        var printedByText = $('<p style="position: absolute; bottom: 30px; right: 30px; color: grey;"><b></p>');
        _el.append(printedByText);

            var nw = window.open('','_blank','width=1000,height=900,top=50,left=200')
                nw.document.write(_el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                        end_loader()
                    }, 300);
                }, (750));
                
            
        })
</script>
<!--**THIS IS NOT THE OFFICIAL COPY OF YOUR GRADES. VIRTUAL GRADES BASED ON FINAL GRADE RECORDED BY YOUR SUBJECT TEACHER**</b>-->