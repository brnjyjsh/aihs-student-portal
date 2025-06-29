<?php
require_once('./config.php');
$userID = $_settings->userdata('id');

if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `student_subject` where id = '{$_GET['id']}'");
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
    <form action="" id="academic-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>"><!-- Student Strand --> <!-- Section Dropdown -->
        <div class="row">
            <div class="form-group col-md-4">
                <label for="section_id" class="control-label">Select Section</label>
                <select name="section_id" id="section_id" class="form-control form-control-sm form-control-border" required>
                    <option value="" disabled selected>Select Section</option>
                    <?php 
                    // Fetch available sections
                    $excludedSectionId = 123; // Replace 123 with the ID of the section you want to exclude

                    $sections = $conn->query("SELECT s.id, s.name, gl.name as grade_level_name 
                        FROM section_list s
                        JOIN trsubject t ON s.id = t.section_id 
                        JOIN grade_level_list gl ON s.grade_level_id = gl.id
                        WHERE t.teacher_id = $userID 
                        AND s.delete_flag = 0 
                        AND s.status = 1 
                        AND s.id != $excludedSectionId");

                    $selectedSections = []; // Array to store selected section IDs

                    while($row = $sections->fetch_assoc()):
                        $sectionId = $row['id'];
                        if (in_array($sectionId, $selectedSections)) {
                            continue; // Skip already selected sections
                        }
                        $selectedSections[] = $sectionId;
                    ?>
                        <option value="<?= $sectionId ?>"><?= $row['name'] . ' - ' . $row['grade_level_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <!-- Student Dropdown -->
            <div class="form-group col-md-8">
                <label for="student_id" class="control-label">Select Student</label>
                <select name="student_id" id="student_id" class="form-control form-control-sm form-control-border" required>
                    <option value="" disabled selected>Select Student</option>
                </select>
            </div>
        </div>

        <script>
            document.getElementById('section_id').addEventListener('change', function() {
                var sectionId = this.value;
                var studentDropdown = document.getElementById('student_id');
                studentDropdown.innerHTML = '<option value="" disabled selected>Select Student</option>';

                <?php 
                $students = $conn->query("SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) as student_name, section_id
                    FROM student_list
                    WHERE delete_flag = 0 AND status = 1");
                
                while($row = $students->fetch_assoc()):
                ?>
                    if (<?= $row['section_id'] ?> == sectionId) {
                        var option = document.createElement('option');
                        option.value = <?= $row['id'] ?>;
                        option.textContent = '<?= $row['student_name'] ?>';
                        studentDropdown.appendChild(option);
                    }
                <?php endwhile; ?>
            });
        </script>

                   <div class="row">
                    <div class="form-group col-md-8">
                        <label for="subject_id" class="control-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled selected>Select Subject</option>
                            <?php 
                            // Fetch all available subjects associated with trsubject for the specific teacher
                            $subjectsQuery = $conn->query("SELECT sl.id, sl.name 
                                FROM subject_list sl
                                JOIN trsubject ts ON sl.id = ts.subject_id
                                WHERE sl.delete_flag = 0 AND sl.status = 1 AND ts.teacher_id = $userID");
                            
                            while($row = $subjectsQuery->fetch_assoc()):
                            ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                    <div class="row">
                        <!-- School Year Dropdown -->
                        <div class="form-group col-md-2">
                            <label for="school_year_id" class="control-label">School Year</label>
                            <select name="school_year_id" id="school_year_id" class="form-control form-control-sm form-control-border" required>
                                <option value="" disabled <?= !isset($school_year_id) ? "selected" : "" ?>></option>
                                <?php 
                                $school_year = $conn->query("SELECT * FROM `school_year_list` where delete_flag = 0 and `status` = 1 ".(isset($deartment_id)? " or id = '{$school_year_id}'" : "")." order by `name` asc");
                                while($row = $school_year->fetch_assoc()):
                                ?>
                                    <option value="<?= $row['id'] ?>" <?= isset($school_year_id) && $school_year_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- quarter Dropdown -->
                        <div class="form-group col-md-3">
                            <label for="quarter_id" class="control-label">Quarter</label>
                            <select name="quarter_id" id="quarter_id" class="form-control form-control-sm form-control-border" required>
                                <option value="" disabled <?= !isset($quarter_id) ? "selected" : "" ?>></option>
                                <?php 
                                $quarter = $conn->query("SELECT * FROM `quarter_list` where delete_flag = 0 and `status` = 1 ".(isset($deartment_id)? " or id = '{$quarter_id}'" : "")." order by `name` asc");
                                while($row = $quarter->fetch_assoc()):
                                ?>
                                    <option value="<?= $row['id'] ?>" <?= isset($quarter_id) && $quarter_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Semester Dropdown -->
                        <div class="form-group col-md-3">
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
                        </div>
                    </div>
                </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#amount').focus();
            $('#section_id1').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#student_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#subject_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#school_year_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#quarter_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#semester_id').select2({
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