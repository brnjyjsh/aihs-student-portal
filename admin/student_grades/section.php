<style>
    .img-thumb-path{
        width:100px;
        height:80px;
        object-fit:scale-down;
        object-position:center center;
    }
</style>
<div class="content py-2">
<div class="card card-outline card-navy rounded-0 shadow">
	<div class="card-header">
		<h3 class="card-title" style="font-weight: bold;">Select Section</h3>
		<?php if($_settings->userdata('type') == 1):?>
			<div class="card-tools">
                <a href="./?page=student_grades" class="btn btn-default border btn-sm btn-flat"><i class="fa fa-angle-left"></i> Back</a>
            </div>
		<?php endif; ?>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-hover table-striped table-responsive">
				<colgroup>
					<col width="1%">
					<col width="5%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="1%">
					<col width="1%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-navy text-light">
						<th>#</th>
						<th>Strand/Track</th>
						<th>Section Name</th>
						<th>Grade Level</th>
						<th>Description</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$id = $_GET['id'];
						$qry = $conn->query("SELECT c.*,d.name as strand from `section_list` c inner join strand_list d on c.strand_id = d.id where c.delete_flag = 0 AND strand_id = $id order by c.`name` asc ");
						$qry2 = $conn->query("SELECT c.*,d.name as grade_level from `section_list` c inner join grade_level_list d on c.grade_level_id = d.id where c.delete_flag = 0 order by c.`name` asc ");
						while($row = $qry->fetch_assoc()):
					?>

						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['strand'] ?></p></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['name'] ?></p></td>
							<td class=""><p class="m-0 truncate-1"><?php 
							// Loop through the results of the second query to find the corresponding grade_level
							while ($row2 = $qry2->fetch_assoc()):
								if ($row2['id'] == $row['id']) {
									echo $row2['grade_level'];
									// Reset the pointer in the second query result set
									$qry2->data_seek(0);
									break;
								}
							endwhile;
        					?></p></td>
							<td class=""><p class="m-0 truncate-1"><?php echo $row['description'] ?></p></td>
							<td class="text-center">
							<?php 
							switch ($row['status']){
								case 1:
									echo '<span class="rounded-pill badge badge-success bg-gradient-teal px-3">Active</span>';
									break;
								case 0:
									echo '<span class="rounded-pill badge badge-danger bg-gradient-danger px-3">Inactive</span>';
									break;
							}
							?>
						</td>
						<td align="center">
							<?php 
							if ($row['status'] == 1) {
								echo '<a href="./?page=student_grades/view_section&id=' . $row['id'] . '" class="btn btn-flat btn-default btn-sm border">Select</a>';
							} else {
								echo '<button class="btn btn-flat btn-default btn-sm border" disabled>Select</button>';
							}
							?>
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
<script>
	$(document).ready(function(){
        /*$('#create_new').click(function(){
			uni_modal("Add New grade_level","grade_level/manage_grade_level.php")
		})
		$('.view_data').click(function(){
			uni_modal("grade_level Details","grade_level/view_grade_level.php?id="+$(this).attr('data-id'))
		})
        $('.edit_data').click(function(){
			uni_modal("Update grade_level Details","grade_level/manage_grade_level.php?id="+$(this).attr('data-id'))
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this grade_level permanently?","delete_grade_level",[$(this).attr('data-id')])
		})*/
		$('.table td, .table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 3 }
            ],
        });
	})
	
	function delete_grade_level($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_grade_level",
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
</script>