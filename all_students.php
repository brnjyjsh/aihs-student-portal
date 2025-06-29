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

if(isset($_GET['student_id'])){
    $qry = $conn->query("SELECT *, CONCAT(lastname,', ', firstname,' ', middlename) as student_fullname FROM `student_list` where id = '{$_GET['student_id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
$studentID = isset($_GET['student_id']) ? $_GET['student_id'] : '';
?>
<style>
#sys_logo{
        width:5em;
        height:5em;
        object-fit:scale-down;
        object-position:center center;
    }
th {
    color: #dddddd;
    background-color: #001F3F;
}
</style>

<div class="content">
    <div class="card card-outline card-navy shadow rounded-0">
            <div class="card-header">
                <h5 class="card-title"></label>Teacher ID: <?= isset($userID) ? $userID : 'N/A' ?></h5>
                <div class="card-tools">
                <button class="btn btn-default border btn-sm btn-flat bg-navy" type="button" id="add_academic"><i class="fa fa-plus"></i> Add Student</button>
                    <!--<a id="backButton" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back</a>-->
                    <script>
                        document.getElementById('backButton').addEventListener('click', function() {
                            window.history.back(); // Use JavaScript to navigate back
                        });
                    </script>
                </div>
            </div>
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
                            } else {
                                echo "No current quarter or semester with status = 1 found.";
                                exit; // Exit if there's no active quarter or semester
                            }
                            ?>
        <div class="card-body">
            <div class="container-fluid" id="outprint"> 
        </div>
                <fieldset>
                    <h2 class="text-muted">Masterlist of All Subjects</h2>
                    <table class="table table-bordered table-striped table-responsive" id="section_subject">
                    <colgroup>
                            <col width="1%">
                            <col width="15%">
                            <col width="7%">
                            <col width="15%">
                            <col width="7%">
                            <col width="7%">
                            <col width="8%">
                            <col width="5%">
                            <col width="1%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-dark">
                            <th class="py-1 text-center">#</th>
                                <th class="py-1 text-center">Student Name</th>
                                <th class="py-1 text-center">Subject Code</th>
                                <th class="py-1 text-center">Subject</th>
                                <th class="py-1 text-center">School Yr.</th>
                                <th class="py-1 text-center">Quarter</th>
                                <th class="py-1 text-center">Section</th>  
                                <th class="py-1 text-center">Grade</th>
                                <th class="py-1 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->prepare("SELECT a.*, c.name AS subject, c.subject_code, d.name AS school_year, e.name AS semester, f.name AS quarter, g.grade AS student_grade, s.roll, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as student_name, ss.name AS section, gl.name AS grade_level
                            FROM student_subject a
                            INNER JOIN subject_list c ON a.subject_id = c.id 
                            INNER JOIN school_year_list d ON a.school_year_id = d.id 
                            INNER JOIN semester_list e ON a.semester_id = e.id
                            INNER JOIN quarter_list f ON a.quarter_id = f.id
                            LEFT JOIN (
                                SELECT student_subject_id, grade
                                FROM student_grade
                            ) g ON a.id = g.student_subject_id
                            INNER JOIN trsubject h ON a.subject_id = h.subject_id AND h.teacher_id = ?
                            LEFT JOIN student_list s ON a.student_id = s.id
                            LEFT JOIN section_list ss ON h.section_id = ss.id
                            LEFT JOIN grade_level_list gl ON ss.grade_level_id = gl.id
                            WHERE (h.subject_id IS NOT NULL OR h.subject_id IS NULL)
                            AND a.school_year_id = '{$activeSchoolYearID}' 
                            AND f.name = '{$activeQuarterName}'
                            AND h.section_id = a.section_id");
                            
    
                            $qry->bind_param("i", $userID);
                            $qry->execute(); 
                            
                            $studentID = isset($_GET['s.id']) ? $_GET['s.id'] : ''; // Assuming student_id is present in the URL parameters
                            $result = $qry->get_result(); // Get the result set
                            
                            while ($row = $result->fetch_assoc()):
                                // Your code to process each row goes here
                            ?>
                         <tr>
        <td class="text-center"><?php echo $i++; ?></td>
        <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['student_name'] ?></span></td>
        <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject_code'] ?></span></td>
        <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject'] ?></span></td>
        <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['school_year'] ?></span></td>

        <td class="px-2 py-1 align-middle text-center" data-quarter-id="<?= $row['quarter_id'] ?>"><span class=""><?= $row['quarter'] ?></span></td>
        <td class="px-2 py-1 align-middle text-center">
            <span class=""><?= $row['grade_level'] ?> - <?= $row['section'] ?></span>
        </td>
        <td class="px-2 py-1 align-middle"  style="text-align: center">
            <span class="<?= (empty($row['student_grade']) ? 'text-danger' : 'text-success') ?>">
                <?= (empty($row['student_grade']) ? 'Not Graded' : $row['student_grade']) ?>
            </span>
        </td>

        <td class="px-2 py-1 align-middle text-center"> 
            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                Action
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <?php
                    // Check if there are existing records in student_grade for the current academic record
                    $student_grade_check = $conn->query("SELECT COUNT(*) as total FROM student_grade WHERE student_subject_id = '{$row['id']}'");
                    $grade_count = $student_grade_check->fetch_assoc()['total'];

                    // Generate dropdown items based on the existence of records
                    if ($grade_count == 0) {
                     // If no records exist, display the "Add Grade" option
                        echo '<a class="dropdown-item add_grade" href="javascript:void(0)" data-id ="' . $row['id'] . '"><span class="fa fa-plus text-primary"></span> Add Grade</a>';
                    } else {
                    // If records exist, display the "Edit Grade" option
                        echo '<a class="dropdown-item edit_grade" href="javascript:void(0)" data-id ="' . $row['id'] . '"><span class="fa fa-edit text-primary"></span> Edit Grade</a>';
                    }
                ?>
            </div>
        </td>
    </tr>
<?php endwhile;
    $qry->close(); // Close the prepared statement
?>
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
            <img src="<?= validate_image($_settings->info('logo')) ?>" class="img-circle" id="sys_logo" alt="System Logo">
        </div>
        <div class="col-8">
            <h4 class="text-center"><b><?= $_settings->info('name') ?></b></h4>
        </div>
        <div class="col-2"></div>
    </div>
</noscript>
<script>
    $(function() {
        $('.add_grade').click(function(){
            uni_modal("Edit Grade Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","add_grade.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-large')
        })
        $('#add_academic').click(function(){
            uni_modal("Add Student","manage_academic.php?student_id=<?= isset($id) ? $id : "" ?>",'mid-large')
        })
        $('.edit_academic').click(function(){
            uni_modal("Edit Academic Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","manage_academict.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-large')
        })
        $('.edit_grade').click(function(){
            uni_modal("Edit Grade","manage_grade.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-large')
        })
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 3 }
            ],
        });
        
        $('#print').click(function(){
            start_loader()
            $('#trsubject').dataTable().fnDestroy()
            var _h = $('head').clone()
            var _p = $('#outprint').clone()
            var _ph = $($('noscript#print-header').html()).clone()
            var _el = $('<div>')
            _p.find('tr.bg-gradient-dark').removeClass('bg-gradient-dark')
            _p.find('tr>td:last-child,tr>th:last-child,colgroup>col:last-child').remove()
            _p.find('.badge').css({'border':'unset'})
            _el.append(_h)
            _el.append(_ph)
            _el.find('title').text('section Records - Print View')
            _el.append(_p)


            var nw = window.open('','_blank','width=1000,height=900,top=50,left=200')
                nw.document.write(_el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                        end_loader()
                        $('.table').dataTable({
                            columnDefs: [
                                { orderable: false, targets: 5 }
                            ],
                        });
                    }, 300);
                }, (750));
                
            
        })
    })
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
</script>
