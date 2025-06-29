<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `trsubject` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }else{
        echo "<center><small class='text-muted'>Unkown trsubject ID.</small</center>";
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
<?php
// Query to get the current quarter and corresponding semester with status = 1
$query = $conn->query("SELECT q.id AS quarter_id, q.name AS quarter_name, s.name AS semester_name 
    FROM `quarter_list` q 
    INNER JOIN `semester_list` s ON 
    (q.name IN ('First Quarter', 'Second Quarter') AND s.name = 'First Semester') OR 
    (q.name IN ('Third Quarter', 'Fourth Quarter') AND s.name = 'Second Semester') 
    WHERE q.status = 1 AND s.status = 1");

if ($query->num_rows > 0) {
    $data = $query->fetch_assoc();
    $activeQuarterID = $data['quarter_id'];
    $activeQuarterName = $data['quarter_name'];
    $activeSemesterName = $data['semester_name'];
} else {
    exit; // Exit if there's no active quarter or semester
}
?>
<?php
    require_once('../../config.php'); // Include your database connection and configuration file

    // Query to get the current school year with status = 1
    $querySchoolYear = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");

     if ($querySchoolYear->num_rows > 0) {
        $schoolYearData = $querySchoolYear->fetch_assoc();
        $activeSchoolYearID = $schoolYearData['id']; // Get the active school year ID
         $activeSchoolYearName = $schoolYearData['name'];
    } else {
    exit; // Exit if there's no active school year
    }
?>
<div class="container-fluid">
    <form action="" id="trsubject-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <input type="hidden" name="teacher_id" value="<?php echo isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '' ?>">
                <input type="hidden" name="school_year_id" value="<?php echo isset($activeSchoolYearID) ? $activeSchoolYearID : ''; ?>">
                <input type="hidden" name="quarter_id" value="<?php echo isset($activeQuarterID) ? $activeQuarterID : ''; ?>">
                    <div class="row">
                        <!-- Strand Dropdown -->
                            <div class="form-group col-md-6">
                                <label for="strand_id" class="control-label">Strand</label>
                                <select name="strand_id" id="strand_id" class="form-control form-control-sm form-control-border" required>
                                    <option value="" disabled <?= !isset($strand_id) ? "selected" : "" ?>></option>
                                    <?php 
                                    $strand = $conn->query("SELECT * FROM `strand_list` where delete_flag = 0 and `status` = 1 AND description != 'General Subjects' order by `name` asc");
                                    while($row = $strand->fetch_assoc()):
                                    ?>
                                        <option value="<?= $row['id'] ?>" <?= isset($strand_id) && $strand_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        <!-- Section Dropdown -->
                        <div class="form-group col-md-6">
                            <label for="section_id" class="control-label">Section</label>
                            <select name="section_id" id="section_id" class="form-control form-control-sm form-control-border" <?= isset($_GET['id']) ? '' : 'required' ?>>
                                <option value="" disabled <?= !isset($section_id) ? "selected" : "" ?>></option>
                                <?php 
                                $section = $conn->query("SELECT s.*, gl.name as grade_level_name
                                                        FROM `section_list` s
                                                        INNER JOIN `grade_level_list` gl ON s.grade_level_id = gl.id
                                                        WHERE s.delete_flag = 0 AND s.status = 1 " . (isset($department_id) ? " OR s.id = '{$section_id}'" : "") . "
                                                        ORDER BY s.name ASC");
                                while($row = $section->fetch_assoc()):
                                ?>
                                    <option value="<?= $row['id'] ?>" <?= isset($section_id) && $section_id == $row['id'] ? 'selected' : '' ?>>
                                        <?= $row['name'] . ' - ' . $row['grade_level_name'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <?php if (isset($_GET['id'])) : ?>
                                        <small class="text-info"><i>Leave this blank if you don't want to change.</i></small>
                                    <?php endif; ?>
                        </div>
                    </div>
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
                                <select name="subject_id" id="subject_id" class="form-control form-control-sm form-control-border" <?= isset($_GET['id']) ? '' : 'required' ?>>
                                    <option value="" disabled selected>Select Subject</option>
                                </select>
                                <?php if (isset($_GET['id'])) : ?>
                                        <small class="text-info"><i>Leave this blank if you don't want to change.</i></small>
                                    <?php endif; ?>
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
                                                            WHERE delete_flag = 0 AND status = 1 AND strand_id = 6 OR strand_id = {$row['strand_id']}
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
                                var selectedStrand = strandDropdown.val();
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

                    </div>
                    

        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal').on('shown.bs.modal',function(){
            $('#amount').focus();
            $('#school_year_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#semester_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#quarter_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#strand_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#section_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
            $('#subject_id').select2({
                placeholder:'Please Select Here',
                width:"100%",
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal #trsubject-form').submit(function(e){
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
                url:_base_url_+"classes/Master.php?f=save_trsubject",
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