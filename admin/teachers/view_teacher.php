<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT *, CONCAT(lastname,', ', firstname,' ', middlename) as fullname FROM `teacher_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<div class="content py-2">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h5 class="card-title" style="font-weight: bold;">Teacher Details</h5>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary btn-flat" href="./?page=teachers/manage_teacher&id=<?= isset($id) ? $id : '' ?>"><i class="fa fa-edit"></i> Edit</a>
                <button class="btn btn-sm btn-danger btn-flat" id="delete_teacher"><i class="fa fa-trash"></i> Delete</button>
                <button class="btn btn-sm btn-navy bg-navy btn-flat" type="button" id="add_trsubject"><i class="fa fa-plus"></i> Assign Subject</button>
                <button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="update_status">Updated Status</button>
                <button class="btn btn-sm btn-success bg-success btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                <a href="./?page=teachers" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back to List</a>
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
                </div>
                <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px;">Academic Details </H5>
                        <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Employee ID:</label> <?= isset($roll) ? $roll : 'N/A' ?>
                            </div>
                        </div>
                        
                    </div>
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Personal Information:</H5>
                    <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Name:</label> <?= isset($fullname) ? $fullname : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                    </div>
                        <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Sex:</label> <?= isset($gender) ? $gender : 'N/A' ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Date of Birth:</label> <?= isset($dob) ? date("M d, Y",strtotime($dob)) : 'N/A' ?>

                            </div>
                        </div>
                    </div>
                    <div class="row px-2">
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
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Portal Account:</H5>
                        <div class="row px-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-muted">Username:</label> <?= isset($username) ? $username : 'N/A' ?>
    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-muted">Initial Password:</label> 
                                    <?= isset($dob) ? str_replace('-', '', $dob) : 'N/A' ?>
                                </div>
                            </div>
                            <div class="col-md-2">
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
                            </div>
                        </div>

                        </div>
                    <fieldset class="border-bottom">
                </fieldset>
                <fieldset>
                    <legend class="text-muted py-2">Teacher Subject</legend>
                    <table class="table table-stripped table-bordered table-responsive" id="outprint">
                        <colgroup>
                            <col width="1%">
                            <col width="5%">
                            <col width="5%">
                            <col width="5%">
                            <col width="25%">
                            <col width="10%">
                            <col width="5%">
                        </colgroup>
                        <thead>
                            <tr class="bg-gradient-navy">
                                <th class="py-1 text-center">#</th>
                                <th class="py-1 text-center">School Year</th>
                                <th class="py-1 text-center">Quarter</th>   
                                <th class="py-1 text-center">Strand/Track</th>
                                <th class="py-1 text-center">Subject Name</th>
                                <th class="py-1 text-center">Section</th>
                                <th class="py-1 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $i = 1;
                            $teacher_subject = $conn->query("SELECT a.*, c.name AS subject, d.name AS strand, e.name AS section, f.name AS quarter, g.name AS school_year, e.strand_id AS section_strand_id FROM `trsubject` a 
                            INNER JOIN subject_list c ON a.subject_id = c.id 
                            INNER JOIN strand_list d ON c.strand_id = d.id 
                            INNER JOIN section_list e ON a.section_id = e.id
                            INNER JOIN quarter_list f ON a.quarter_id = f.id
                            INNER JOIN school_year_list g ON a.school_year_id = g.id
                            WHERE teacher_id = '{$id}'");
                            while ($row = $teacher_subject->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="px-2 py-1 align-middle text-center"><?= $i++; ?></td>
                                <td class="px-2 py-1 align-middle">
                                    <span class=""><?= $row['school_year'] ?></span>
                                </td>
                                <td class="px-2 py-1 align-middle">
                                    <span class=""><?= $row['quarter'] ?></span>
                                </td>
                                </td>
                                <td class=""><p class="m-0 truncate-1">
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
                                    </p></td>
                                <td class="px-2 py-1 align-middle">
                                    <span class=""><?= $row['subject'] ?></span>
                                </td>
                                <td class=""><p class="m-0 truncate-1">
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
                                <td class="px-2 py-1 align-middle text-center no-print">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item edit_trsubject" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_trsubject" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
            <h3 class="text-center"><b>Teacher Records</b></h3>
            <br>
            <br>
            <div class="row">
                </div>
                <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px;">Academic Details </H5>
                        <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Employee ID:</label> <?= isset($roll) ? $roll : 'N/A' ?>
                            </div>
                        </div>
                        
                    </div>
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Personal Information:</H5>
                    <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Name:</label> <?= isset($fullname) ? $fullname : 'N/A' ?>
                                <!--<div class="pl-4"></div>-->
                            </div>
                        </div>
                    </div>
                        <div class="row px-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Sex:</label> <?= isset($gender) ? $gender : 'N/A' ?>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label text-muted">Date of Birth:</label> <?= isset($dob) ? date("M d, Y",strtotime($dob)) : 'N/A' ?>

                            </div>
                        </div>
                    </div>
                    <div class="row px-2">
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
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Portal Account:</H5>
                        <div class="row px-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-muted">Username:</label> <?= isset($username) ? $username : 'N/A' ?>
    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label text-muted">Initial Password:</label> 
                                    <?= isset($dob) ? str_replace('-', '', $dob) : 'N/A' ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label text-muted no-print">Status:</label> 
                                <?php 
                                        switch ($status){
                                            case 0:
                                                echo '<span class="rounded-pill badge badge-secondary bg-gradient-secondary px-3 no-print">Inactive</span>';
                                                break;
                                            case 1:
                                                echo '<span class="rounded-pill badge badge-primary bg-gradient-primary px-3 no-print">Active</span>';
                                                break;
                                        }
                                    ?>
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
            uni_modal("Update Status of <b><?= isset($roll) ? $roll : "" ?></b>","teachers/update_status.php?teacher_id=<?= isset($id) ? $id : "" ?>")
        })
        $('#add_trsubject').click(function(){
            uni_modal("Assign Subject for <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","teachers/manage_teacher_subject.php?teacher_id=<?= isset($id) ? $id : "" ?>",'mid-large')
        })
        $('.edit_trsubject').click(function(){
            uni_modal("Edit Assign Subject of <b><?= isset($roll) ? $roll.' - '.$fullname : "" ?></b>","teachers/manage_teacher_subject.php?teacher_id=<?= isset($id) ? $id : "" ?>&id="+$(this).attr('data-id'),'mid-large')
        })
        $('.delete_trsubject').click(function(){
			_conf("Are you sure to delete this Teacher's Assigned Subject?","delete_trsubject",[$(this).attr('data-id')])
		})
        $('#delete_teacher').click(function(){
			_conf("Are you sure to delete this Teacher Information?","delete_teacher",['<?= isset($id) ? $id : '' ?>'])
		})
        $('.view_data').click(function(){
			uni_modal("Report Details","teachers/view_report.php?id="+$(this).attr('data-id'),"mid-large")
		})
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
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
    function delete_teacher($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_teacher",
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
					location.href="./?page=teachers";
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
