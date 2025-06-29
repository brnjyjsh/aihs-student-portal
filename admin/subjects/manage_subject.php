<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `subject_list` where id = '{$_GET['id']}'");
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
	img#cimg{
		height: 17vh;
		width: 25vw;
		object-fit: scale-down;
	}
</style>
<div class="container-fluid">
    <form action="" id="subject-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="strand_id" class="control-label">Strand</label>
            <select name="strand_id" id="strand_id" class="form-control form-control-sm form-control-border" required>
                <option value="" disabled <?= !isset($deartment_id) ? "selected" : "" ?>></option>
                <?php 
                    $strand = $conn->query("SELECT * FROM `strand_list` WHERE delete_flag = 0 AND `status` = 1 ".(isset($deartment_id)? " OR id = '{$strand_id}'" : "")." ORDER BY CASE WHEN `description` = 'General Subjects' THEN 0 ELSE 1 END, `name` ASC");
                    while($row = $strand->fetch_assoc()):
                ?>

                    <option value="<?= $row['id'] ?>" <?= isset($strand_id) && $strand_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="subject_code" class="control-label">Subject Code</label>
            <input type="text" name="subject_code" id="subject_code" class="form-control form-control-border" placeholder="Enter Subject Code" value ="<?php echo isset($subject_code) ? $subject_code : '' ?>" required>
        </div>

        <form action="" id="subject-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="grade_level_id" class="control-label">Grade Level</label>
            <select name="grade_level_id" id="grade_level_id" class="form-control form-control-sm form-control-border" required>
                <option value="" disabled <?= !isset($grade_level_id) ? "selected" : "" ?>></option>
                <?php 
                $grade_level = $conn->query("SELECT * FROM `grade_level_list` where delete_flag = 0 and `status` = 1 ".(isset($deartment_id)? " or id = '{$grade_level_id}'" : "")." order by `name` asc");
                while($row = $grade_level->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>" <?= isset($grade_level_id) && $grade_level_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!--<form action="" id="subject-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="semester_id" class="control-label">Semester</label>
            <select name="semester_id" id="semester_id" class="form-control form-control-sm form-control-border" required>
                <option value="" disabled <?= !isset($semester_id) ? "selected" : "" ?>></option>
                <?php 
                $semester = $conn->query("SELECT * FROM `semester_list` where delete_flag = 0 and `status` = 1 ".(isset($deartment_id)? " or id = '{$semester_id}'" : "")." order by `name` asc");
                while($row = $semester->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>" <?= isset($semester_id) && $semester_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>-->

        <form action="" id="subject-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="subject_type_id" class="control-label">Subject Type</label>
            <select name="subject_type_id" id="subject_type_id" class="form-control form-control-sm form-control-border" required>
                <option value="" disabled <?= !isset($subject_type_id) ? "selected" : "" ?>></option>
                <?php 
                $subject_type = $conn->query("SELECT * FROM `subject_type` where delete_flag = 0 and `status` = 1 ".(isset($deartment_id)? " or id = '{$subject_type_id}'" : "")." order by `name` asc");
                while($row = $subject_type->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>" <?= isset($subject_type_id) && $subject_type_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name" class="control-label">Subject Name</label>
            <input type="text" name="name" id="name" class="form-control form-control-border" placeholder="Enter Subject Name" value ="<?php echo isset($name) ? $name : '' ?>" required>
        </div>
        <!--
            <div class="form-group">
                <label for="semester" class="control-label">semester</label>
                <select name="semester" id="semester" class="form-control form-control-sm  form-control-border rounded-0" required>
                    <option <?= !isset($semester) || (isset($semester) && $semester == 'First semester') ? 'selected' : '' ?>>First semester</option>
                    <option <?= (isset($semester) && $semester == 'Second semester') ? 'selected' : '' ?>>Second semester</option>
                </select>
            </div>
            <div class="form-group">
                <label for="type" class="control-label">Type</label>
                <select name="status" class="form-control form-control-sm form-control-border rounded-0" required>
                    <option value="1" <?= (isset($status) && $status == 1) ? 'selected' : '' ?>>Core</option>
                    <option value="2" <?= (isset($status) && $status == 2) ? 'selected' : '' ?>>Contextualize</option>
                    <option value="3" <?= (isset($status) && $status == 3) ? 'selected' : '' ?>>Specialization</option>
                </select>
            </div>
                -->
        <div class="form-group">
            <label for="description" class="control-label">Description</label>
            <textarea rows="3" name="description" id="description" class="form-control form-control-sm rounded-0" required><?php echo isset($description) ? ($description) : '' ?></textarea>
        </div>
        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-control form-control-sm form-control-border" required>
                <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#strand_id').select2({
                placeholder:'Please Select Here',
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('#grade_level_id').select2({
                placeholder:'Please Select Here',
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('#semester_id').select2({
                placeholder:'Please Select Here',
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('#subject_type_id').select2({
                placeholder:'Please Select Here',
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
            $('#status').select2({
                placeholder:'Please Select Here',
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal #subject-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_subject",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>