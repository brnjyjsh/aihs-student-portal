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
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT a.*, c.name AS subject_name, c.subject_code, d.name AS strand_name, e.name AS section_name, g.name AS school_year_name, h.name AS quarter
    FROM `trsubject` a 
    INNER JOIN subject_list c ON a.subject_id = c.id 
    INNER JOIN strand_list d ON c.strand_id = d.id 
    INNER JOIN section_list e ON a.section_id = e.id
    INNER JOIN school_year_list g ON a.school_year_id = g.id
    INNER JOIN quarter_list h ON a.quarter_id = h.id
    WHERE a.id = '{$_GET['id']}'");
    
    if (isset($subject_id)) {
        // Your code that uses $subject_id
    } else {
        // Handle the case when $subject_id is not set
    }

    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
                $$k = $v;
        }
        $subject_id = $res['subject_id']; // Set the value of $subject_id
    }
}
?>
<?php
// Query to get the current quarter and corresponding semester with status = 1
$query = $conn->query("SELECT q.id AS quarter_id, q.name AS quarter_name, s.name AS semester_name 
    FROM `quarter_list` q 
    INNER JOIN `semester_list` s ON 
    (q.name IN ('First Quarter', 'Second Quarter') AND s.name = 'First Semester') OR 
    (q.name IN ('Third Quarter', 'Fourth Quarter') AND s.name = 'Second Semester') 
    WHERE q.status = 1 AND s.status = 1");

if ($query->num_rows > 0) {
    $data = $query->fetch_assoc();
    $activeQuarterID = $data['quarter_id'];
    $activeQuarterName = $data['quarter_name'];
    $activeSemesterName = $data['semester_name'];
} else {
    exit; // Exit if there's no active quarter or semester
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
<div class="content py-2">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h4 class="card-title"></label><b><?= isset($subject_name) ? $subject_name : 'N/A' ?></b></h4>
            <div class="card-tools">
                <button class="btn btn-default border btn-sm btn-flat bg-navy" type="button" id="add_regular"><i class="fa fa-plus"></i> Add Students</button>
                <button class="btn btn-default border btn-sm btn-flat bg-navy" type="button" id="add_irregular"><i class="fa fa-plus"></i> Add Irregular Student</button>
                <button class="btn btn-sm btn-success bg-success btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                    <!--<a id="backButton" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back</a>-->
                <script>
                        document.getElementById('backButton').addEventListener('click', function() {
                            window.history.back(); // Use JavaScript to navigate back
                        });
                </script>
                <a id="backButton" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
                <script>
                    document.getElementById('backButton').addEventListener('click', function() {
                        window.history.back(); // Use JavaScript to navigate back
                    });
                </script>
            </div>
        </div>
        <div class="card-body" >
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
                    <!--<div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Teacher Subject ID:</label> <?= isset($id) ? $id : 'N/A' ?>
                            <div class="pl-4"></div>
                        </div>
                    </div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Subject Code:</label> <?= isset($subject_code) ? $subject_code : 'N/A' ?>
                            <div class="pl-4"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                                <!--<div class="pl-4"></div>-->
                            </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">School Year:</label> <?= isset($school_year_name) ? $school_year_name : 'N/A' ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Quarter:</label> <?= isset($quarter) ? $quarter : 'N/A' ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
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
                <fieldset class="border-bottom">  
                </div>
                </fieldset>
                <fieldset>
                <legend class="text-muted">List of Students</legend>
                <table class="table table-bordered table-hover table-striped table-responsive" id="outprint">
                    <colgroup>
                        <col width="1%">
                        <col width="15%">
                        <col width="35%">
                        <col width="5%">
                        <col width="5%">
                        <col width="10%">
                        <col width="1%">
                    </colgroup>
                    <thead>
                        <tr class="bg-gradient-navy text-light">
                            <th class="py-1 text-center">#</th>
                            <th class="py-1 text-center">Learner Reference Number (LRN)</th>
                            <th class="py-1 text-center">Student Name</th> 
                            <th class="py-1 text-center">Gender</th>
                            <th class="py-1 text-center">Classification</th> 
                            <th class="py-1 text-center">Grade</th> 
                            <th class="py-1 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT 
                        a.*, 
                        s.roll, 
                        CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) AS full_name, 
                        g.grade AS student_grade, 
                        a.quarter_id AS quarter_id, 
                        a.school_year_id AS school_year_id, 
                        s.gender AS gender, 
                        s.student_status_id AS student_status_id
                    FROM 
                        student_subject a
                    LEFT JOIN 
                        student_list s ON a.student_id = s.id
                    LEFT JOIN (
                        SELECT 
                            student_subject_id, 
                            grade
                        FROM 
                            student_grade
                    ) g ON a.id = g.student_subject_id
                    WHERE 
                        a.subject_id = $subject_id
                        AND a.section_id = $section_id
                        AND a.quarter_id = $activeQuarterID 
                        AND a.school_year_id = $activeSchoolYearID
                    ORDER BY 
                        CASE WHEN s.gender = 'MALE' THEN 0 ELSE 1 END, -- Order by male first, then female
                        s.lastname ASC, 
                        s.gender ASC, 
                        s.student_status_id ASC;
                    ");
                        
                        while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class=""><p class="m-0 truncate-1"><?php echo $row['roll'] ?></p></td> <!-- LRN -->
                            <td class=""><p class="m-0 truncate-1"><?php echo $row['full_name'] ?></p></td> <!-- Student Name -->
                            <td class="" style="text-align: center"><p class="m-0 truncate-1" ><?php echo $row['gender'] ?></p></td> <!-- Gender -->
                            <td class="px-2 py-1 align-middle" style="text-align: center">
                                <?php
                                // Display the Classification based on student_status_id
                                if (isset($row['student_status_id'])) {
                                    $status_query = $conn->query("SELECT name FROM student_status WHERE id = {$row['student_status_id']}");
                                    $status_data = $status_query->fetch_assoc();
                                    $classification = $status_data['name'];
                                    echo $classification;
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td class="px-2 py-1 align-middle" style="text-align: center">
                                <span class="<?= (empty($row['student_grade']) ? 'text-muted' : ($row['student_grade'] < 75 ? 'text-danger' : 'text-success')) ?>">
                                    <?= (empty($row['student_grade']) ? 'Not Graded' : $row['student_grade']) ?>
                                </span>
                            </td>
                            <td class="px-2 py-1 align-middle text-center"> 
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon <?= (!empty($row['student_grade']) ? 'disabled' : '') ?>" data-toggle="dropdown" <?= (!empty($row['student_grade']) ? 'disabled' : '') ?>>
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item add_grade" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-plus text-primary"></span> Add Grade</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_academic" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                </div>
                            </td>
                        </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </fieldset>
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
            <h3 class="text-center"><b><?= isset($subject_name) ? $subject_name : 'N/A' ?></b></h3>
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
                    <!--<div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Teacher Subject ID:</label> <?= isset($id) ? $id : 'N/A' ?>
                            <div class="pl-4"></div>
                        </div>
                    </div>-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Subject Code:</label> <?= isset($subject_code) ? $subject_code : 'N/A' ?>
                            <div class="pl-4"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                                <!--<div class="pl-4"></div>-->
                            </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">School Year:</label> <?= isset($school_year_name) ? $school_year_name : 'N/A' ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Quarter:</label> <?= isset($quarter) ? $quarter : 'N/A' ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
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
    $(function() {
        $('.add_grade').click(function(){
            uni_modal("Add Grade", "add_grade.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'custom-modal')
        })
        $('#add_regular').click(function(){
            uni_modal("Add Student for <b><?= isset($subject_code) ? $subject_code.' - '.$subject_name : "" ?></b>","regular_student.php?trsubject_id=<?= isset($id) ? $id : "" ?>",'mid-xl')
        })
        $('#add_irregular').click(function(){
            uni_modal("Add Irregular Student for <b><?= isset($subject_code) ? $subject_code.' - '.$subject_name : "" ?></b>","irregular_student.php?trsubject_id=<?= isset($id) ? $id : "" ?>",'mid-xl')
        })
        $('.edit_academic').click(function(){
            uni_modal("Edit Academic Record of <b><?= isset($subject_code) ? $subject_code.' - '.$subject_name : "" ?></b>","manage_student.php?trsubject_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-xl')
        })
        $('.delete_academic').click(function(){
			_conf("Are you sure to delete this Student's Academic Record?","delete_academic",[$(this).attr('data-id')])
		})
        $('.edit_grade').click(function(){
            uni_modal("Edit Grade", "manage_grade.php?student_id=<?= isset($id) ? $id : "" ?>&id=" + $(this).attr('data-id'), 'custom-modal')
        })
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
        });
    })
    function delete_academic($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_academic",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    function delete_trsubject($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_trsubject",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    function delete_section($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_section",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.href="./?page=sections";
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
    $('#print').click(function(){
    start_loader();
    $('#academic-history').dataTable().fnDestroy();
    var _h = $('head').clone();
    var _p = $('#outprint').clone();
    var _ph = $($('noscript#print-header').html()).clone();
    var _el = $('<div>');
    _p.find('tr.bg-gradient-dark').removeClass('bg-gradient-dark');
    _p.find('tr>td:last-child,tr>th:last-child,colgroup>col:last-child').remove();
    _p.find('.badge').css({'border':'unset'});
    _el.append(_h);
    _el.append(_ph);
    _el.find('title').text('Student Records - Print View');
    _el.append(_p);

    // Get current date and format as MM-DD-YYYY
    var currentDate = new Date().toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'});

    // Uppercase and bold the firstname
    var firstNameICT = '<?php echo strtoupper(ucwords($_settings->userdata('firstname'))) ?>';
	var lastNameICT = '<?php echo strtoupper(ucwords($_settings->userdata('lastname'))) ?>';

    // Create a table with 3x2 cells
    var table = $('<br><br><br><table>').css({'width': '100%', 'margin-bottom': '10px'});
    var row1 = $('<tr>');
    row1.append($('<td>').css({'text-align': 'center', 'width': '50%'}).html('<u>'+'______________________________________'+'</u>'));
    row1.append($('<td>').css({'text-align': 'center', 'width': '50%'}).html('<u>' +'_____' + currentDate + '______' + '</u>')); // Adjust width here
    var row2 = $('<tr>');
    row2.append($('<td>').css({'text-align': 'center', 'width': '50%'}).html('Presented by <b>' + firstNameICT + ' ' + lastNameICT +'</b>')); // Uppercase and bold firstname
    row2.append($('<td>').css({'text-align': 'center', 'width': '50%'}).text('Date'));
    var row3 = $('<tr>'); // Additional row
    row3.append($('<td>').css({'text-align': 'center', 'width': '50%'}).text('Subject Teacher'));
    row3.append($('<td>').css({'text-align': 'center', 'width': '50%'}).text('  ')); // Adjust content of additional column
    table.append(row1, row2, row3); // Append the additional row
    _el.append(table);

    var nw = window.open('','_blank','width=1000,height=900,top=50,left=200');
    nw.document.write(_el.html());
    nw.document.close();
    setTimeout(() => {
        nw.print();
        setTimeout(() => {
            nw.close();
            end_loader();
        }, 300);
    }, (750)); 
});
</script>