<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT c.*, d.description as strand, e.name as grade_level FROM `section_list` c 
    inner join strand_list d on c.strand_id = d.id 
    inner join grade_level_list e on c.grade_level_id  = e.id
    where c.id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
	@media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
	}
	.hide-from-page {
        display: block;
    }
</style>
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

    function hasGrades($sectionId, $activeSchoolYearID)
{
    global $conn;

    $query = $conn->query("SELECT COUNT(*) as count FROM student_grade sg
        INNER JOIN student_subject ss ON sg.student_subject_id = ss.id
        WHERE ss.section_id = '{$sectionId}' AND ss.school_year_id = '{$activeSchoolYearID}' AND (sg.grade IS NOT NULL OR sg.grade = 0)");

    $result = $query->fetch_assoc();

    return $result['count'] > 0;
}
// Query for Students Nominated for Promotion
$qry_promoted = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS fullname 
                              FROM student_list sl
                              WHERE section_id = '{$id}' 
                              AND school_year_id = $activeSchoolYearID
                              AND NOT EXISTS (
                                  SELECT 1 
                                  FROM student_subject ss 
                                  INNER JOIN student_grade sg ON ss.id = sg.student_subject_id 
                                  WHERE ss.student_id = sl.id 
                                  AND (sg.grade IS NULL OR sg.grade = 0)
                              )
                              ORDER BY gender DESC, CONCAT(lastname, ', ', firstname, ' ', middlename) ASC");



// Fetch the list of not promoted students
$qry_not_promoted = $conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS fullname 
                                  FROM student_list sl
                                  WHERE section_id = '{$id}' 
                                  AND school_year_id = $activeSchoolYearID
                                  AND EXISTS (
                                      SELECT 1 
                                      FROM student_subject ss 
                                      INNER JOIN student_grade sg ON ss.id = sg.student_subject_id 
                                      WHERE ss.student_id = sl.id 
                                      AND (sg.grade IS NULL OR sg.grade = 0)
                                  )
                                  ORDER BY gender DESC, CONCAT(lastname, ', ', firstname, ' ', middlename) ASC");




// Get a list of grades that are not graded yet
$grades_not_graded = [];
$query_grades = $conn->query("SELECT DISTINCT grade FROM student_grade WHERE grade IS NOT NULL");
while ($row = $query_grades->fetch_assoc()) {
    $grades_not_graded[] = $row['grade'];
}
?>

<div class="content py-2">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title" style="font-weight: bold;" ></label><?= isset($grade_level) ? $grade_level : 'N/A' ?> - </label> <?= isset($name) ? $name : 'N/A' ?></h5>
            <div class="card-tools">
            <?php
            // Check if the grade level is Grade 11 (assuming Grade 11 has grade_level_id = 1)
            if (isset($grade_level_id) && $grade_level_id == 1) {
                echo '<button class="btn btn-sm btn-muted bg-green btn-flat" type="button" id="promote_student"><i class="fa fa-angle-double-up"></i> Promote All Students</button>';
            }
            ?>
            <!-- Button to trigger modal -->
            <?php
// Check if the grade level is Grade 11 (assuming Grade 11 has grade_level_id = 1)
if (isset($grade_level_id) && $grade_level_id == 1) {
    echo '<button type="button" class="btn btn-sm btn-muted bg-primary btn-flat" data-toggle="modal" data-target="#promotionStatusModal"><i class="fa fa-eye"></i> View Promotion Status</button>';
}
?>

<!-- Modal -->
<div class="modal fade" id="promotionStatusModal" tabindex="-1" role="dialog" aria-labelledby="promotionStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promotionStatusModalLabel">Promotion Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Table for students nominated for promotion -->
                <div class="row">
    <div class="col-md-12">
        <h6>Students Nominated for Promotion</h6>
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="promoted_students">
                <colgroup>
                            <col width="1%">
                            <col width="5%">
                            <col width="8%">
                            <col width="2%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="py-1 text-center">#</th>
                        <th class="py-1 text-center">Learner Reference Number</th>
                        <th class="py-1 text-center">Student Name</th>
                        <th class="py-1 text-center">Gender</th>
                                    <!-- Add more columns as needed -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                while ($row = $qry_promoted->fetch_assoc()) :
                                ?>
                                    <tr>
                                        <td class="py-1 text-center"><?php echo $i++; ?></td>
                                        <td class="py-1 text-center"><?php echo $row['roll'] ?></td>
                                        <td class="py-1 text-center"><?php echo $row['fullname'] ?></td>
                                        <td class="py-1 text-center"><?php echo $row['gender'] ?></td>
                                        <!-- Populate additional columns as needed -->
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>
                <!-- Table for students with subjects not yet graded -->
                <div class="row">
    <div class="col-md-12">
        <h6>Students with Subjects Not Yet Graded</h6>
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="not_graded_students">
                <colgroup>
                            <col width="1%">
                            <col width="5%">
                            <col width="8%">
                            <col width="2%">
                            <col width="2%" class="no-print">
                </colgroup>
                <thead>
                    <tr>
                        <th class="py-1 text-center">#</th>
                        <th class="py-1 text-center">Learner Reference Number</th>
                        <th class="py-1 text-center">Student Name</th>
                        <th class="py-1 text-center">Gender</th>
                        <th class="py-1 text-center no-print">Action</th> <!-- New column for View Subjects button -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($row = $qry_not_promoted->fetch_assoc()) :
                    ?>
                    <tr>
                        <td class="py-1 text-center"><?php echo $i++; ?></td>
                        <td class="py-1 text-center"><?php echo $row['roll'] ?></td>
                        <td class="py-1 text-center"><?php echo $row['fullname'] ?></td>
                        <td class="py-1 text-center"><?php echo $row['gender'] ?></td>
                        <td class="py-1 text-center no-print">
                            <a href="./?page=student_grades/view_subject&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">View Subjects</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

            <button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
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
                </style>
                <div class="row">
                    <!--<div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Section ID:</label> <?= isset($id) ? $id : 'N/A' ?>
                            <div class="pl-4"></div>
                        </div>
                    </div>
                    -->
                    <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Strand/Track:</label> <?= isset($strand) ? $strand : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-muted">School Year:</label> <?= isset($activeSchoolYearName) ? $activeSchoolYearName : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label text-muted">Status:</label> 
                            <?php 
                                    switch ($status){
                                        case 0:
                                            echo '<span class="rounded-pill badge badge-secondary bg-gradient-secondary px-3">Inactive</span>';
                                            break;
                                        case 1:
                                            echo '<span class="rounded-pill badge badge-primary bg-gradient-primary px-3">Active</span>';
                                            break;
                                    }
                                ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
                </div>
                <fieldset class="border-bottom">
                        
                    </div>
                </fieldset>
                <fieldset>
                    <legend class="text-muted">List of Students</legend>
                </form>
                    <table class="table table-stripped table-bordered table-responsive" id="outprint">
                        <colgroup>
                            <col width="1%">
                            <col width="13%">
                            <col width="25%">
                            <col width="5%">
                            <col width="5%">
                            <col width="7%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-navy">
                                <th class="py-1 text-center">#</th>
                                <th class="py-1 text-center">Learner Reference Number (LRN)</th>
                                <th class="py-1 text-center">Student Name</th> 
                                <th class="py-1 text-center">Gender</th> 
                                <th class="py-1 text-center">Classification</th> 
                                <th class="py-1 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
						$i = 1;
						$qry = $conn->query("SELECT *, concat(lastname, ', ', firstname, ' ', middlename) as fullname 
                        FROM student_list
                        WHERE section_id = '{$id}' AND school_year_id = $activeSchoolYearID
                        ORDER BY gender DESC, concat(lastname, ', ', firstname, ' ', middlename) ASC");
                        while($row = $qry->fetch_assoc()):
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class=""><p class="m-0 truncate-1"><?php echo $row['roll'] ?></p></td> <!-- LRN -->
                                <td class=""><p class="m-0 truncate-1"><?php echo $row['fullname'] ?></p></td> <!-- Student Name -->
                                <td class="" style="text-align: center"><p class="m-0 truncate-1"><?php echo $row['gender'] ?></p></td>
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
                            <td align="center">
								 <a href="./?page=student_grades/view_subject&id=<?= $row['id'] ?>" class="btn btn-flat btn-default btn-sm border"><i class="fa fa-eye"></i> View Subjects</a>
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
            <h3 class="text-center"><b><?= isset($grade_level) ? $grade_level : 'N/A' ?> - </label> <?= isset($name) ? $name : 'N/A' ?> Records</b></h3>
            <br>
            <br>
            <div class="row">
                    <!--<div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Section ID:</label> <?= isset($id) ? $id : 'N/A' ?>
                            <div class="pl-4"></div>
                        </div>
                    </div>
                    -->
                    <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Strand/Track:</label> <?= isset($strand) ? $strand : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label text-muted">School Year:</label> <?= isset($activeSchoolYearName) ? $activeSchoolYearName : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                </div>
        </div>
        <div class="col-2"></div>
    </div>
</noscript>
<script>
    $(function() {
        $('#update_status').click(function(){
            uni_modal("Update Status of <b><?= isset($roll) ? $roll : "" ?></b>","update_status.php?section_id=<?= isset($id) ? $id : "" ?>")
        })
$(document).ready(function(){
    $('#promote_student').click(function(){
        // Check if any students have missing grades
        <?php
        // Query to check if any student in the section has missing or zero grades
        $query = "SELECT COUNT(*) AS count FROM student_list sl
                  INNER JOIN student_subject ss ON sl.id = ss.student_id
                  INNER JOIN student_grade sg ON ss.id = sg.student_subject_id
                  WHERE sl.section_id = $id AND (sg.grade IS NULL OR sg.grade = 0)";

        $result = $conn->query($query);
        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $count = $row['count'];
            if ($count > 0) {
                echo "if (!confirm('There are $count student(s) who have not yet been graded. Do you want to proceed?')) { return false; }";
            }
        }
        ?>
        
        // If all students are graded or user confirms, proceed with promoting all students
        uni_modal("Promote All Students", "student_grades/promote_student.php?section_id=<?= isset($id) ? $id : "" ?>");
    });
});

        $('#add_academic').click(function(){
            uni_modal("Add Academic Record for <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","student_grades/apply_subjects.php?student_id=<?= isset($id) ? $id : "" ?>",'mid-large')
        })
        $('#add_trsubject').click(function(){
            uni_modal("Add trsubject Record for <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","sections/manage_section_subject.php?section_id=<?= isset($id) ? $id : "" ?>",'mid-large')
        })
        $('.edit_trsubject').click(function(){
            uni_modal("Edit A trsubject Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","sections/manage_section_subject.php?section_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_trsubject').click(function(){
			_conf("Are you sure to delete this section's trsubject Record?","delete_trsubject",[$(this).attr('data-id')])
		})
        $('#delete_section').click(function(){
			_conf("Are you sure to delete this section Information?","delete_section",['<?= isset($id) ? $id : '' ?>'])
		})
        $('.view_data').click(function(){
			uni_modal("Report Details","sections/view_report.php?id="+$(this).attr('data-id'),"mid-large")
		})
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 3 }
            ],
        });
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
    _el.find('title').text('Section Records - Print View');
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
    row3.append($('<td>').css({'text-align': 'center', 'width': '50%'}).text('ICT Coordinator'));
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
