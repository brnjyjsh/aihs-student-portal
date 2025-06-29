<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `student_subject` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }else{
        echo "<center><small class='text-muted'>Unkown student_subject ID.</small</center>";
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
                <input type="hidden" name="student_id" value="<?php echo isset($_GET['student_id']) ? $_GET['student_id'] : '' ?>">
                    <div class="row">
                        <!-- Strand and Section Dropdowns -->
                        <?php 
                        $s_strand = "SELECT * FROM student_list WHERE id = '{$_GET['student_id']}' ";
                        $s_result = $conn->query($s_strand);

                        if ($s_result->num_rows > 0) {
                            $s_data = $s_result->fetch_assoc();
                            $r_strand = $s_data['strand_id'];
                            $r_section = $s_data['section_id']; // Add this line to get section_id
                        } else {
                            // Handle the case where no data is found for the student_id
                            echo "<center><small class='text-muted'>Unknown student ID.</small></center>";
                            exit;
                        }
                        ?>
                    </div>
                    <input type="hidden" name="section_id" id="section_id" value="<?php echo isset($r_section) ? $r_section : ''; ?>">

                        <script>
                            
                        $(document).ready(function() {
                            // Get references to the dropdowns
                            var strandDropdown = $("#strand_id");
                            var sectionDropdown = $("#section_id");

                            // Define an object to map strand IDs to available sections
                            var sectionsByStrand = {
                                <?php 
                                $section = $conn->query("SELECT * FROM `section_list` where delete_flag = 0 and `status` = 1 order by `name` asc");
                                while($row = $section->fetch_assoc()):
                                ?>
                                <?= $row['strand_id'] ?>: [
                                    <?php 
                                    $sections = $conn->query("SELECT s.*, gl.name as grade_level_name
                                                            FROM `section_list` s
                                                            INNER JOIN `grade_level_list` gl ON s.grade_level_id = gl.id
                                                            WHERE s.delete_flag = 0 AND s.status = 1 AND s.strand_id = {$row['strand_id']}
                                                            ORDER BY s.name ASC");
                                    $sectionOptions = [];
                                    while($secRow = $sections->fetch_assoc()):
                                        $sectionOptions[] = "{ id: " . $secRow['id'] . ", name: '" . $secRow['name'] . "', grade_level_name: '" . $secRow['grade_level_name'] . "' }";
                                    endwhile;
                                    echo implode(",", $sectionOptions);
                                    ?>
                                ],
                                <?php endwhile; ?>
                            };

                            // Update the section dropdown based on the selected strand
                            strandDropdown.on("change", updateSectionDropdown);

                            // Trigger the updateSectionDropdown function on page load
                            updateSectionDropdown();

                            function updateSectionDropdown() {
                                var selectedStrand = strandDropdown.val();
                                var availableSections = sectionsByStrand[selectedStrand];

                                // Clear existing options
                                sectionDropdown.empty();

                                // Add a default option
                                sectionDropdown.append("<option value='' disabled selected>Select Section</option>");

                                // Add new options based on the selected strand
                                if (availableSections) {
                                    availableSections.forEach(function(section) {
                                        sectionDropdown.append(`<option value="${section.id}">${section.name} - ${section.grade_level_name}</option>`);
                                    });
                                }
                            }
                        });
                        </script>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="subject_id" class="control-label">Subject</label>
                                <select name="subject_id" id="subject_id" class="form-control form-control-sm form-control-border" required>
                                    <option value="" disabled selected>Select Subject</option>
                                </select>
                            </div>
                        </div>

                        <script>
                        $(document).ready(function() {
                            // Get references to the dropdowns
                            var strandDropdown = $("#strand_id");
                            var subjectDropdown = $("#subject_id");

                            // Define an object to map strand IDs to available subjects
                            var subjectsByStrand = {
                                <?php 
                                $subject = $conn->query("SELECT * FROM `subject_list` where delete_flag = 0 and `status` = 1 order by `name` asc");
                                while($row = $subject->fetch_assoc()):
                                ?>
                                <?= $row['strand_id'] ?>: [
                                    <?php 
                                    $subjects = $conn->query("SELECT *
                                                            FROM `subject_list`
                                                            WHERE delete_flag = 0 AND status = 1 AND strand_id = 6 OR strand_id = $r_strand
                                                            ORDER BY name ASC");
                                    $subjectOptions = [];
                                    while($subRow = $subjects->fetch_assoc()):
                                        $subjectOptions[] = "{ id: " . $subRow['id'] . ", name: '" . $subRow['name'] . "' }";
                                    endwhile;
                                    
                                    echo implode(",", $subjectOptions);
                                    ?>
                                ],
                                <?php endwhile; ?>
                            };
                            // Update the subject dropdown based on the selected strand
                            strandDropdown.on("change", updateSubjectDropdown);

                            // Trigger the updateSubjectDropdown function on page load
                            updateSubjectDropdown();

                            function updateSubjectDropdown() {
                                var selectedStrand = <?php echo $r_strand; ?>;
                                var availableSubjects = subjectsByStrand[selectedStrand];

                                // Clear existing options
                                subjectDropdown.empty();

                                // Add a default option
                                subjectDropdown.append("<option value='' disabled selected>Select Subject</option>");

                                // Add new options based on the selected strand
                                if (availableSubjects) {
                                    availableSubjects.forEach(function(subject) {
                                        subjectDropdown.append(`<option value="${subject.id}">${subject.name}</option>`);
                                    });
                                }
                            }
                        });
                        </script>
                        <div class="row">
                            <!-- School Year Dropdown -->
                            <div class="form-group col-md-6">
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
                            <div class="form-group col-md-6">
                                <label for="quarter_id" class="control-label">Quarter</label>
                                <select name="quarter_id" id="quarter_id" class="form-control form-control-sm form-control-border" required>
                                    <option value="" disabled <?= !isset($quarter_id) ? "selected" : "" ?>></option>
                                    <?php 
                                    $quarter = $conn->query("SELECT * FROM `quarter_list` where delete_flag = 0 ".(isset($deartment_id)? " or id = '{$quarter_id}'" : "")." and `status` = 1 order by `id` asc");
                                    while($row = $quarter->fetch_assoc()):
                                    ?>
                                        <option value="<?= $row['id'] ?>" <?= isset($quarter_id) && $quarter_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <!-- Semester Dropdown 
                            <div class="form-group col-md-4">
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
                        </div>
                    </div>
    </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#amount').focus();
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
                url:_base_url_+"classes/Master.php?f=save_academic1",
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