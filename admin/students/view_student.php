<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT *, CONCAT(lastname,', ', firstname,' ', middlename) as fullname FROM `student_list` where id = '{$_GET['id']}'");
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
<div class="content py-2">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title" style="font-weight: bold;">Student Details</h5>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary btn-flat" href="./?page=students/manage_student&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
                <button class="btn btn-sm btn-danger btn-flat" id="delete_student"><i class="fa fa-trash"></i> Delete</button>
                <!--<<button class="btn btn-sm btn-navy bg-navy btn-flat" type="button" id="add_academic"><i class="fa fa-plus"></i> Add Academic</button>-->
                <button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="update_status"><i class="fas fa-wrench"></i> Updated Status</button>
                <button class="btn btn-sm btn-info bg-blue btn-flat" type="button" id="update_student_promotion"><i class="fa fa-angle-double-up"></i> Update School Year</button>
                <button class="btn btn-sm btn-success bg-success btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                <a href="./?page=students" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
            </div>
        </div>
        <div class="card-body" >
            <div class="container-fluid" id="outprint">
                <style>
                    #sys_logo{
                        width:5em;
                        height:5em;
                        object-fit:scale-down;
                        object-position:center center;
                    }
                </style>
                <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px;">Academic Details </H5>
                <div class="row px-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Learner Reference Number (LRN):</label> <?= isset($roll) ? $roll : 'N/A' ?>
                            <!--<div class="pl-4"></div>-->
                        </div>
                    </div>
                    <div class="col-md-2">
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
                <div class="row px-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Grade & Section:</label>
                            <?php 
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
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label text-muted">School Year:</label>
                            <?php 
                            if (isset($school_year_id)) {
                                // Retrieve the strand name from the strand_list table
                                $school_year = $conn->query("SELECT name FROM `school_year_list` WHERE id = $school_year_id");
                                $school_year_name = $school_year->fetch_assoc()['name'];
                                echo $school_year_name;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row px-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label text-muted">Classification:</label>
                            <?php 
                            if (isset($student_status_id)) {
                                // Retrieve the strand name from the strand_list table
                                $student_status = $conn->query("SELECT name FROM `student_status` WHERE id = $student_status_id");
                                $student_status_name = $student_status->fetch_assoc()['name'];
                                echo $student_status_name;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <fieldset class="border-bottom">
                <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Personal Information </H5>
                    <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Name:</label> <?= isset($fullname) ? $fullname : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                    </div>
                    <div class="row row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Sex:</label> <?= isset($gender) ? $gender : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Date of Birth:</label> <?= isset($dob) ? date("M d, Y",strtotime($dob)) : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                        
                    </div>
                    <div class="row px-2" >
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Contact Number:</label> <?= isset($contact) ? $contact : 'N/A' ?>
                                <div class="pl-4"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Email Address:</label> <?= isset($email_address) ? $email_address : 'N/A' ?> <!--Address-->
                                <div class="pl-4"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Guardian:</label> <?= isset($guardian_name) ? $guardian_name : 'N/A' ?>
                                <div class="pl-4"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label text-muted">Guardian Contact Number:</label> <?= isset($guardian_contact) ? $guardian_contact : 'N/A' ?> <!--Address-->
                                <div class="pl-4"></div>
                            </div>
                        </div>
                    </div>
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Portal Account</H5>
                    <div class="row px-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-muted">Username:</label> <?= isset($username) ? $username : 'N/A' ?>
                                    <!--<div class="pl-4"></div>-->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-muted">Initial Password:</label> 
                                    <?= isset($dob) ? str_replace('-', '', $dob) : 'N/A' ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                        <div class="form-group no-print">
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
                </fieldset>
                
                <fieldset>
                <br>
                <div class="row">
                    <div>     
                        <legend for="quarterFilter">List of Subject</legend>
                    </div>
                </div>
                <br>
                <table class="table table-bordered table-striped table-bordered table-responsive" id="academic-history">
                        <colgroup>
                            <col width="1%">
                            <col width="7%">
                            <col width="20%">
                            <col width="9%">
                            <col width="6%">
                            <col width="8%">
                            <col width="8%">
                            <col width="1%" class="no-print">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-navy">
                                <th class="py-1 text-center">#</th>
                                <th class="py-1 text-center">Subject Code</th>
                                <th class="py-1 text-center">Subject</th>
                                <th class="py-1 text-center">Grade & Section</th>
                                <th class="py-1 text-center">School Yr.</th>
                                <th class="py-1 text-center">Quarter</th>   
                                <th class="py-1 text-center">Grade</th>
                                <th class="py-1 text-center no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $academics = $conn->query("SELECT a.*, c.name AS subject, c.subject_code, d.name AS school_year, f.name AS quarter, g.grade AS student_grade, s.name AS section_name, gl.name AS grade_level_name
                            FROM student_subject a
                            INNER JOIN subject_list c ON a.subject_id = c.id 
                            INNER JOIN school_year_list d ON a.school_year_id = d.id 
                            INNER JOIN quarter_list f ON a.quarter_id = f.id
                            LEFT JOIN section_list s ON a.section_id = s.id
                            LEFT JOIN grade_level_list gl ON s.grade_level_id = gl.id
                            LEFT JOIN student_grade g ON a.id = g.student_subject_id
                            WHERE a.student_id = '{$id}'
                            ORDER BY a.school_year_id DESC, a.quarter_id DESC");

                            while ($row = $academics->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject_code'] ?></span></td>
                                <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject'] ?></span></td>
                                <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['grade_level_name'] . ' - ' . $row['section_name'] ?></span></td>
                                <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['school_year'] ?></span></td>
                                <td class="px-2 py-1 align-middle text-center" data-quarter-id="<?= $row['quarter_id'] ?>"><span class=""><?= $row['quarter'] ?></span></td>
                                <td class="px-2 py-1 align-middle text-center" style="text-align: center">
                                    <span class="<?= (empty($row['student_grade']) ? 'text-muted' : ($row['student_grade'] < 75 ? 'text-danger' : 'text-success')) ?>">
                                        <?= (empty($row['student_grade']) ? 'Not Graded' : $row['student_grade']) ?>
                                    </span>
                                </td>
                                <td class="px-2 py-1 align-middle text-center no-print"> 
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon no-print" data-toggle="dropdown">
                                            Action
                                        <span class="sr-only no-print">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu no-print" role="menu">
                                        <a class="dropdown-item edit_academic no-print" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary no-print"></span> Edit Subject</a>
                                        <?php
                                        // Assuming this block of code is within a loop for each academic record ($row)

                                        // Check if there are existing records in student_grade for the current academic record
                                        $student_grade_check = $conn->query("SELECT COUNT(*) as total FROM student_grade WHERE student_subject_id = '{$row['id']}'");
                                        $grade_count = $student_grade_check->fetch_assoc()['total'];

                                        // Generate dropdown items based on the existence of records
                                        if ($grade_count == 0) {
                                            // If no records exist, display the "Add Grade" option
                                            echo '<div class="dropdown-divider no-print"></div><a class="dropdown-item add_grade" href="javascript:void(0)" data-id ="' . $row['id'] . '"><span class="fa fa-plus text-primary no-print"></span> Add Grade</a>';
                                        } else {
                                            // If records exist, display the "Edit Grade" option
                                            echo '<div class="dropdown-divider no-print"></div><a class="dropdown-item edit_grade" href="javascript:void(0)" data-id ="' . $row['id'] . '"><span class="fa fa-edit text-primary no-print"></span> Edit Grade</a>';
                                        }
                                        ?>
                                        <div class="dropdown-divider no-print"></div>
                                        <a class="dropdown-item delete_academic no-print" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
            <h3 class="text-center"><b>Student Records</b></h3>
            <br>
            <br>
        </div>
        <div class="col-2"></div>
    </div>

</noscript>
<script>
    $(function() {
        $('#update_status').click(function(){
            uni_modal("Update Status of <b><?= isset($roll) ? $roll : "" ?></b>","students/update_status.php?student_id=<?= isset($id) ? $id : "" ?>")
        })
        $('#update_student_promotion').click(function(){
            uni_modal("Update School Year for <b><?= isset($roll) ? $roll : "" ?></b>","students/update_student_promotion.php?student_id=<?= isset($id) ? $id : "" ?>")
        })
        $('#add_academic').click(function(){
            uni_modal("Add Subject for <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","students/manage_academic.php?student_id=<?= isset($id) ? $id : "" ?>",'mid-large')
        })
        $('.edit_academic').click(function(){
            uni_modal("Edit Academic Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","students/manage_academic.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-large')
        })
        $('.add_grade').click(function(){
            uni_modal("Edit Grade Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","students/add_grade.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-xl')
        })
        $('.edit_grade').click(function(){
            uni_modal("Edit Grade Record of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","students/manage_grade.php?student_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-xl')
        })
        $('.delete_academic').click(function(){
			_conf("Are you sure to delete this Student's Academic Record?","delete_academic",[$(this).attr('data-id')])
		})
        $('#delete_student').click(function(){
			_conf("Are you sure to delete this Student Information?","delete_student",['<?= isset($id) ? $id : '' ?>'])
		})
        $('.view_data').click(function(){
			uni_modal("Report Details","students/view_report.php?id="+$(this).attr('data-id'),"mid-large")
		})
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 7 }
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
    function delete_student($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_student",
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
					location.href="./?page=students";
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
</script>
