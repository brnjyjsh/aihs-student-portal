<?php
if($_settings->userdata('type') == 1) {
    echo "<script>window.location.href='/sis/admin/';</script>";
    exit();
}

if($_settings->userdata('type') == 3):
$user = $conn->query("SELECT s.*, d.name as strand, c.name as section,
    CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname 
    FROM student_list s
    INNER JOIN strand_list d ON s.strand_id = d.id
    INNER JOIN section_list c ON s.section_id = c.id
    WHERE s.id = '{$_settings->userdata('id')}'");

foreach($user->fetch_array() as $k =>$v){
    $$k = $v;
}
endif;
if($_settings->userdata('type') == 2):
$user = $conn->query("SELECT s.*, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname FROM teacher_list s WHERE s.id = '{$_settings->userdata('id')}'");

foreach($user->fetch_array() as $k =>$v){
    $$k = $v;
}
endif;
// Define the image source based on the gender
$imageSrc = ($gender === 'Male') ? 'uploads/male_placeholder.png' : 'uploads/female_placeholder.png';
?>
<style>
    .car-cover{
        width:10em;
    }
    .car-item .col-auto{
        max-width: calc(100% - 12em) !important;
    }
    .car-item:hover{
        transform:translate(0, -4px);
        background:#a5a5a521;
    }
    .banner-img-holder{
        height:25vh !important;
        width: calc(100%);
        overflow: hidden;
    }
    .banner-img{
        object-fit:scale-down;
        height: calc(100%);
        width: calc(100%);
        transition:transform .3s ease-in;
    }
    .car-item:hover .banner-img{
        transform:scale(1.3)
    }
    .welcome-content img{
        margin:.5em;
    }
    .student-img {
        object-fit: scale-down;
        object-position: center center;
        height: 200px;
        width: 200px;
    }
    th {
        color: #dddddd;
        background-color: #001F3F;
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
    .bgimg {
    background-image: url('uploads/homebg.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50% 50%;
    }

</style>


<div class="content col-lg-12 py-2">
    <div class="card card-outline card-navy shadow rounded-0 bgimg">
        <div class="card-header rounded-0">
            <div class="container-fluid">
                <legend class="text-center bg-navy" style=" border: 1px black solid; width: 263px; background: #195905; color:#d1a827; text-align:left;">Basic Information</legend>
                <div class="card-body rounded-0">
                    <div class="container-fluid">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-12 py-2">
                                        <center>
                                            <img src="<?= $imageSrc ?>" alt="Student Image" class="img-fluid student-img w3-card-4 bg-gradient-dark border">
                                        </center>
                                    </div>
                                    <div class="col-lg-8 col-sm-12 py-3">
                                        <dl>
                                            <dd class="pl-4" style="color: white;"><h2><?= ucwords($fullname) ?></h2></dd>
                                            <?php if($_settings->userdata('type') == 3): ?>
                                            <dd class="pl-4" style="color: white;"><?= ucwords($roll) ?></dd>
                                            <?php endif; ?>
                                            <?php if($_settings->userdata('type') == 2): ?>
                                            <dd class="pl-4" style="color: white;"><?= ucwords($roll) ?></dd>
                                            <?php endif; ?>
                                            <dd class="pl-4" style="color: white;"><?= ucwords($gender) ?></dd>
                                            <?php if($_settings->userdata('type') == 3): ?>
                                            <dd class="pl-4" style="color: white;"><?= ucwords($strand) ?></dd>
                                            <!--<dt class="text-navy">Grade Section:</dt>-->
                                            <dd class="pl-4" style="color: white;">
                                            <?php
                                            if (isset($section_id)) {
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
                                            </dd>
                                            <?php endif; ?>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-outline card-navy shadow rounded-0">
                    <h3 class="text-center py-3">DepEd Mission & Vision</h3>
                                <div class="accordion" id="missionVisionAccordion">
                                <div class="card-body rounded-0">
                            <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="card py-3 bg-navy">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#missionCollapse" aria-expanded="false" aria-controls="missionCollapse">
                                                        <b style="color: white;">Mission</b>
                                                        </button>
                                                <div id="missionCollapse" class="collapse" aria-labelledby="missionHeading" data-parent="#missionVisionAccordion">
                                                    <div class="card-body">
                                                        <p>To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete basic education where:</p>
                                                        <ul>
                                                            <li>Students learn in a child-friendly, gender-sensitive, safe, and motivating environment.</li>
                                                            <li>Teachers facilitate learning and constantly nurture every learner.</li>
                                                            <li>Administrators and staff ensure an enabling and supportive environment for effective learning to happen.</li>
                                                            <li>Family, community, and other stakeholders are actively engaged in developing life-long learners.</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card py-3 bg-navy">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#visionCollapse" aria-expanded="false" aria-controls="visionCollapse">
                                                            <b style="color: white;">Vision</b>
                                                        </button>
                                                <div id="visionCollapse" class="collapse" aria-labelledby="visionHeading" data-parent="#missionVisionAccordion">
                                                    <div class="card-body">
                                                        <p>We dream of Filipinos who passionately love their country and whose values and competencies enable them to realize their full potential and contribute meaningfully to building the nation.</p>
                                                        <p>As a learner-centered public institution, the Department of Education continuously improves itself to better serve its stakeholders.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-lg-4">
                    <div class="card card-outline card-navy shadow rounded-0 bg-navy">
                        <div class="card-body rounded-0 text-center">
                            <div class="container-fluid py-2">
                                <h3 class="text-center">Active School Year | Semester</h3>
                                <hr>
                                <div class="">
                                    <div class="text-center">
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
                                </div>
                                <div class="">
                                <div class="text-center">
                                    
                                        <h5>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
