<?php

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
                        </h5>
                            <!-- Conditionally display "First Semester" or "Second Semester" based on the active quarter -->
                            <?php
                            $query = $conn->query("SELECT q.id AS quarter_id, q.name AS quarter_name, s.name AS semester_name 
                                                    FROM `quarter_list` q 
                                                    INNER JOIN `semester_list` s 
                                                    ON 
                                                    ((q.name IN ('First Quarter', 'Second Quarter') AND s.name = 'First Semester') 
                                                    OR 
                                                    (q.name IN ('Third Quarter', 'Fourth Quarter') AND s.name = 'Second Semester')) 
                                                    WHERE q.status = 1 AND s.status = 1");

                            if ($query->num_rows > 0) {
                                $data = $query->fetch_assoc();
                                $activeQuarterName = $data['quarter_name'];
                                $activeSemesterName = $data['semester_name'];
                                $activeQuarterID = $data['quarter_id'];
                            } else {
                                echo "No current quarter or semester with status = 1 found.";
                                exit; // Exit if there's no active quarter or semester
                            }
                            ?>

<?php
$strand_ids = array(
    1 => "STEM",
    2 => "ABM",
    3 => "HUMSS",
    4 => "TVL - HE",
    5 => "TVL - ICT"
);

$students_strand = array();

// Assuming you have a database connection established (variable $conn)
if ($conn) {
    foreach ($strand_ids as $strand_id => $strand_label) {
        $sql = "SELECT COUNT(*) as count FROM `student_list` WHERE strand_id = $strand_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $students_strand[] = array("y" => $count, "label" => $strand_label);
        } else {
            $students_strand[] = array("y" => 0, "label" => $strand_label);
        }
    }
}

$grades = array(); 

// Assuming you have a database connection established (variable $conn)
if ($conn) {
    // Assuming school_year_id and quarter_id are fields in the student_subject table
    $sql_graded = "SELECT COUNT(*) AS count FROM student_grade 
                   WHERE student_subject_id IN (SELECT id FROM student_subject 
                                               WHERE school_year_id = $activeSchoolYearID 
                                               AND quarter_id = $activeQuarterID)
                   AND grade > 0";  // Exclude grades with value 0

    $result_graded = $conn->query($sql_graded);

    if ($result_graded && $result_graded->num_rows > 0) {
        $row_graded = $result_graded->fetch_assoc();
        $graded_count = $row_graded['count'];
    }

    // Counting the students not graded
    $sql_not_graded = "SELECT COUNT(*) AS count FROM student_grade 
                       WHERE student_subject_id IN (SELECT id FROM student_subject 
                                                   WHERE school_year_id = $activeSchoolYearID 
                                                   AND quarter_id = $activeQuarterID)
                       AND (grade = 0 OR grade IS NULL)";  // Include grades with value 0 or NULL

    $result_not_graded = $conn->query($sql_not_graded);

    if ($result_not_graded && $result_not_graded->num_rows > 0) {
        $row_not_graded = $result_not_graded->fetch_assoc();
        $not_graded_count = $row_not_graded['count'];
    }

    // Update the $grades array
    $grades = array( 
        array("label" => "Graded", "y" => $graded_count),
        array("label" => "Not Graded", "y" => $not_graded_count)
    );
}


?>

<!--<h1>Welcome to <?php echo $_settings->info('name') ?></h1>-->
<style>
    #website-cover{
        width:100%;
        height:30em;
        object-fit:cover;
        object-position:center center;
    }
</style>
<script>
window.onload = function() {
    CanvasJS.addColorSet("yellow",
                [//colorSet Array

                "#FFC436",
                "#00a8e8",
                "#2E8B57",
                "#3CB371",
                "#90EE90"                
                ]);
    CanvasJS.addColorSet("blue",
                [//colorSet Array

                "#051923",
                "#003554",
                "#006494",
                "#0582ca",
                "#00a6fb"                
                ]);
var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	theme: "light2", // "light2", "dark1", "dark2",
    colorSet: "blue",
	title:{
		text: "No. of Student (per Strand)"
	},
	axisY: {
		title: "No. of Student (per Strand)"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## Student/s",
		dataPoints: <?php echo json_encode($students_strand, JSON_NUMERIC_CHECK); ?>
	}]
});
var chart2 = new CanvasJS.Chart("chartContainer2", {
	theme: "light2", // "light2", "dark1", "dark2",
	animationEnabled: true,
    colorSet: "yellow",
	title: {
		text: "Recorded Grade in SY <?php echo $activeSchoolYearName . ' ' . $activeQuarterName  . ' | ' . $activeSemesterName; ?>"
	},
	data: [{
		type: "pie",
		indexLabel: "{y}",
		yValueFormatString: "#,##",
		indexLabelPlacement: "inside",
		indexLabelFontColor: "#000000",
		indexLabelFontSize: 18,
		indexLabelFontWeight: "bolder",
		showInLegend: true,
		legendText: "{label}",
		dataPoints: <?php echo json_encode($grades, JSON_NUMERIC_CHECK); ?>
	}]
});
chart1.render();
chart2.render();
 
}
</script>
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-book-open"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">No. of Strands Offered</span>
            <span class="info-box-number text-right">
                <?php 
                    $countQuery = $conn->query("SELECT COUNT(*) FROM `strand_list` WHERE delete_flag = 0 AND `status` = 1 AND description != 'General Subjects'");
                    $count = $countQuery->fetch_row()[0];
                    echo $count;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-scroll"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">No. of Subjects</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `subject_list` where delete_flag= 0 and `status` = 1 ")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fa-user-friends"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">No. of Registered Students</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `student_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fas fa-user-alt"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">No. of Registered Teachers</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `teacher_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-3">
        <div class="info-box bg-gradient-light shadow">
            <span class="info-box-icon bg-gradient-navy elevation-1"><i class="fas fas fa-chalkboard-teacher"></i></span>

            <div class="info-box-content">
            <span class="info-box-text">No. of Sections</span>
            <span class="info-box-number text-right">
                <?php 
                    echo $conn->query("SELECT * FROM `section_list`")->num_rows;
                ?>
            </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>
<div class="row">
    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div id="chartContainer1" style="height: 370px; width: 100%;"></div>
        <script src="../canvajs/canvasjs.min.js"></script>
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
            <script src="../canvajs/canvasjs.min.js"></script>
    </div>
</div>
