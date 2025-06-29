<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }

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

<div class="content py-2" >
<div class="card card-outline card-navy rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title" style="font-weight: bold;">Student List</h3>
		<div class="card-tools">
			<button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
			<a href="./?page=students/import_csv" class="btn btn-flat btn-sm btn-success bg-success"><span class="fas fa-file-import"></span> Import CSV</a>
			<a href="./?page=students/manage_student" class="btn btn-flat btn-sm btn-navy bg-navy"><span class="fas fa-plus"></span>  Add New Student</a>
		</div>
		<!-- HTML form to upload the CSV file -->
		<br>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-striped table-responsive " id="outprint">
				<colgroup>
					<col width="1%">
					<col width="20%">
					<col width="30%">
					<col width="5%">
					<col width="10%">
					<col width="15%">
					<col width="5%" class="no-print">
					<col width="7%" class="no-print">
				</colgroup>
				<thead>
					<tr class="bg-gradient-navy text-light">
						<th>#</th>
						<th>Learner Reference Number (LRN)</th>
						<th>Student Name</th>
						<th>Gender</th>
						<th>Strand/Track</th>
						<th>Grade & Section</th>
						<th class="no-print">Status</th>
						<th class="no-print">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from `student_list` order by concat(lastname,', ',firstname,' ',middlename) asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['roll'] ?></p></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['fullname'] ?></p></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['gender'] ?></p></td>
							<td class=""><p class="m-0 truncate-1">
								<?php 
								if (isset($row['strand_id'])) {
									// Retrieve the strand name from the strand_list table
									$strand_id = $row['strand_id'];
									$strand = $conn->query("SELECT name FROM `strand_list` WHERE id = $strand_id");
									$strand_name = $strand->fetch_assoc()['name'];
									echo $strand_name;
								} else {
									echo 'N/A';
								}
								?>
							</p></td>
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
							<td class="text-center no-print">
								<?php 
									switch ($row['status']){
										case 0:
											echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Inactive</span>';
											break; 
										case 1:
											echo '<span class="rounded-pill badge badge-success bg-gradient-success px-3">Active</span>';
											break;
									}
								?>
							</td>
							<td align="center">
								 <a href="./?page=students/view_student&id=<?= $row['id'] ?>" class="btn btn-flat btn-default btn-sm border"><i class="fa fa-eye"></i> View</a>
							</td>
						</tr>
					<?php endwhile; ?>
					
				</tbody>
			</table>
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
            <h3 class="text-center"><b>Student Records</b></h3>
            <br>
            <br>
        </div>
        <div class="col-2"></div>
    </div>

</noscript>


<script>
	$(document).ready(function(){
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
        });
	})
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
					location.reload();
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

