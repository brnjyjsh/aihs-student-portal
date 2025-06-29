<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
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
<div class="content py-2">
<div class="card card-outline card-navy rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title" style="font-weight: bold;">List of Subject</h3>
		<div class="card-tools">
			<button class="btn btn-sm btn-info bg-info btn-flat" type="button" id="print"><i class="fa fa-print"></i> Print</button>
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-navy bg-navy"><span class="fas fa-plus"></span>  Add Subject</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-responsive table-striped" id="outprint">
				<colgroup>
					<col width="2%">
					<col width="7%">
					<col width="10%">
					<col width="30%">
					<col width="10%">	
					<col width="10%">
					<col width="10%">
					<col width="5%" class="no-print">
					<col width="5%" class="no-print">
				</colgroup>
				<thead>
					<tr class="bg-gradient-navy text-light">
						<th class="text-center">#</th>
						<th>Strand/Track</th>
						<th>Subject Code</th>
						<th>Subject Name</th>
						<th>Grade Level</th>
						<th>Type</th>
						<th>Description</th>
						<th class="no-print">Status</th>
						<th class="no-print">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$i = 1;
				$qry = $conn->query("SELECT c.*, d.name as strand, st.name as subject_type_name, gl.name as grade_level
				FROM `subject_list` c
				INNER JOIN strand_list d ON c.strand_id = d.id
				INNER JOIN subject_type st ON c.subject_type_id = st.id
				INNER JOIN grade_level_list gl ON c.grade_level_id = gl.id
				WHERE c.delete_flag = 0
				ORDER BY c.`name` ASC");

				while ($row = $qry->fetch_assoc()):
				?>

				<tr>
					<td class="text-center"><?php echo $i++; ?></td>
					<td class=""><p class="m-0 truncate-1"><?php echo $row['strand'] ?></p></td>
					<td class=""><p class="m-0 truncate-1"><?php echo $row['subject_code'] ?></p></td>
					<td class=""><p class="m-0 truncate-1"><?php echo $row['name'] ?></p></td>
					<td class=""><p class="m-0 truncate-1"><?php echo $row['grade_level'] ?></p></td>
					<td class=""><p class="m-0 truncate-1"><?php echo $row['subject_type_name'] ?></p></td>
					<td class=""><p class="m-0 truncate-1"><?php echo $row['description'] ?></p></td>
					<td class="text-center no-print">
						<?php 
							switch ($row['status']) {
								case 1:
									echo '<span class="rounded-pill badge badge-success bg-gradient-teal px-3 no-print">Active</span>';
									break;
								case 0:
									echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3 no-print">Inactive</span>';
									break;
							}
						?>
					</td>

					<td align="center">
						<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon no-print" data-toggle="dropdown">
							Action
							<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu" role="menu" class="no-print">
							<a class="dropdown-item view_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item edit_data" href="javascript:void(0)" data-id ="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
						</div>
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
            <h3 class="text-center"><b>Subject Records</b></h3>
            <br>
            <br>
        </div>
        <div class="col-2"></div>
    </div>

</noscript>
<script>
	$(document).ready(function(){
        $('#create_new').click(function(){
			uni_modal("Add New Subject","subjects/manage_subject.php")
		})
		$('.view_data').click(function(){
			uni_modal("Subject Details","subjects/view_subject.php?id="+$(this).attr('data-id'))
		})
        $('.edit_data').click(function(){
			uni_modal("Update Subject Details","subjects/manage_subject.php?id="+$(this).attr('data-id'))
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Subject permanently?","delete_subject",[$(this).attr('data-id')])
		})
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 8 }
            ],
        });
	})
	function delete_subject($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_subject",
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
    _el.find('title').text('Subject Records - Print View');
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