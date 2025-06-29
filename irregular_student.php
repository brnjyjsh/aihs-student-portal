<?php
require_once('./config.php');
$userID = $_settings->userdata('id');

// Retrieve section_id and subject_id from trsubject using trsubject_id
$trsubject_id = isset($_GET['trsubject_id']) ? $_GET['trsubject_id'] : '';
if (!empty($trsubject_id)) {
    $trsubject_qry = $conn->query("SELECT section_id, subject_id, school_year_id, quarter_id FROM `trsubject` WHERE id = '$trsubject_id'");
    if ($trsubject_qry->num_rows > 0) {
        $trsubject_data = $trsubject_qry->fetch_assoc();
        $section_id = $trsubject_data['section_id'];
        $subject_id = $trsubject_data['subject_id'];
        $quarter_id = $trsubject_data['quarter_id'];
        $school_year_id = $trsubject_data['school_year_id'];
    } else {
        echo "<center><small class='text-muted'>Unknown TR Subject ID.</small></center>";
        exit;
    }
}

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `student_subject` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }else{
        echo "<center><small class='text-muted'>Unknown Student Subject ID.</small</center>";
        exit;
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
    <form action="" id="academic-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="subject_id" value="<?php echo isset($_GET['subject_id']) ? $_GET['subject_id'] : '' ?>">
        <div class="row">
            <!-- Remove Student Dropdown -->
            <!-- <div class="form-group col-md-12">
                <label for="student_id" class="control-label">Select Student</label>
                <select name="student_id" id="student_id" class="form-control form-control-sm form-control-border" required>
                    <option value="" disabled selected>Select Student</option>
                    <?php
                    // Modify the student query to filter by section_id and subject_id
                    $student_query = $conn->query("SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) as student_name FROM `student_list` WHERE id NOT IN (SELECT student_id FROM `student_subject` WHERE section_id = '$section_id' AND subject_id = '$subject_id') AND section_id = '$section_id'");
                    while ($student = $student_query->fetch_assoc()) :
                    ?>
                        <option value="<?php echo $student['id']; ?>" <?php echo isset($student_id) && $student_id == $student['id'] ? 'selected' : ''; ?>>
                            <?php echo $student['student_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div> -->

            <!-- Add Hidden Input for Section ID -->
            <input type="hidden" name="section_id" value="<?php echo isset($section_id) ? $section_id : ''; ?>">
            <input type="hidden" name="subject_id" value="<?php echo isset($subject_id) ? $subject_id : ''; ?>">
            <input type="hidden" name="school_year_id" value="<?php echo isset($school_year_id) ? $school_year_id : ''; ?>">
            <input type="hidden" name="quarter_id" value="<?php echo isset($quarter_id) ? $quarter_id : ''; ?>">

            <!-- Display All Students in the Section -->
            <div class="form-group col-md-12">
            <center><label class="control-label">Regular Students</label></center>
                <div>
                <center>
                    <button type="button" class="btn btn-primary" id="checkAll">Check All</button>
                    <button type="button" class="btn btn-secondary" id="uncheckAll">Uncheck All</button>
                </center>
                </div>
                <ul class="list-group">
                    <br>
                    <?php
                    // Modify the student query to filter by section_id and exclude those already in student_subject
                    $student_query = $conn->query("SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) as student_name 
                        FROM `student_list` 
                        WHERE section_id = '$section_id' 
                            AND student_status_id = 2
                            AND id NOT IN (
                                SELECT student_id 
                                FROM `student_subject` 
                                WHERE section_id = '$section_id' 
                                    AND subject_id = '$subject_id'
                            )
                        ORDER BY lastname ASC
                    ");
                    if ($student_query->num_rows > 0) {
                        while ($student = $student_query->fetch_assoc()) :
                    ?>
                            <li class="list-group-item clickable-student" data-student-id="<?php echo $student['id']; ?>">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="student_ids[]" value="<?php echo $student['id']; ?>" checked>
                                    <label class="form-check-label">
                                        <?php echo $student['student_name']; ?>
                                    </label>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    <?php } else { ?>
                        <li class="list-group-item">No students available in the list.</li>
                    <?php } ?>
                </ul>
            </div>

            <style>
                .clickable-student {
                    cursor: pointer;
                }
            </style>

            <script>
                $(function () {
                    // Check All
                    $('#checkAll').click(function () {
                        $('input[name="student_ids[]"]').prop('checked', true);
                    });

                    // Uncheck All
                    $('#uncheckAll').click(function () {
                        $('input[name="student_ids[]"]').prop('checked', false);
                    });

                    // Make list items clickable
                    $('.clickable-student').click(function () {
                        var checkbox = $(this).find('input[name="student_ids[]"]');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    });

                    // ... (Your existing JavaScript code)
                });
            </script>
            </div>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#amount').focus();
            $('#student_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal #academic-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            if(_this[0].checkValidity() == false){
                _this[0].reportValidity();
                return false;
            }
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_academic",
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