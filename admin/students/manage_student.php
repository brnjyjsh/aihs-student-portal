<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `student_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}

// Fetch the active school year if not editing an existing student
$activeSchoolYearId = null;
if (!isset($_GET['id'])) {
    $activeSchoolYearQuery = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");
    $activeSchoolYear = $activeSchoolYearQuery->fetch_assoc();
    $activeSchoolYearId = isset($activeSchoolYear['id']) ? $activeSchoolYear['id'] : null;
}
// Add a value of 'type' in the teacher_list table
if (!isset($type)) {
    $type = 3; // You can modify this value as needed
}
?>
<div class="content py-3">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h3 class="card-title"><b><?= isset($id) ? "Update Student Details - ". $roll : "Add Student" ?></b></h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="student_form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <fieldset class="border-bottom">
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px;">Academic Details </H5>
                        <div class="row px-2">
                            <div class="form-group col-md-4">
                                <label for="roll" class="control-label">Learner Reference Number (LRN):</label>
                                <input type="number" onkeydown="lrn(this);" onkeyup="lrn(this);" name="roll" id="roll" oninput="generateUsername()" autofocus value="<?= isset($roll) ? $roll : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <script>
                            function lrn(element)
                            {
                                var max_chars = 12;
                                    
                                if(element.value.length > max_chars) {
                                    element.value = element.value.substr(0, max_chars);
                                    {
                                    alert("You have reached a limit of 12 Digits");
                                    }
                                }
                            }
                            </script>
                            <!-- Strand Dropdown -->
                            <div class="form-group col-md-2">
                                <label for="strand_id" class="control-label">Strand</label>
                                <select name="strand_id" id="strand_id" class="form-control form-control-sm form-control-border" required>
                                    <option value="" disabled <?= !isset($strand_id) ? "selected" : "" ?>></option>
                                    <?php 
                                    $strand = $conn->query("SELECT * FROM `strand_list` WHERE delete_flag = 0 AND `status` = 1 AND description != 'General Subjects' ORDER BY `name` ASC");
                                    while($row = $strand->fetch_assoc()):
                                    ?>
                                        <option value="<?= $row['id'] ?>" <?= isset($strand_id) && $strand_id == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <!-- Section Dropdown -->
                            <div class="form-group col-md-4">
                                <label for="section_id" class="control-label">Section</label>
                                <select name="section_id" id="section_id" class="form-control form-control-sm form-control-border">
                                    <option value="" disabled <?= !isset($section_id) ? "selected" : "" ?>></option>
                                    <?php 
                                    $section = $conn->query("SELECT s.*, gl.name as grade_level_name
                                                            FROM `section_list` s
                                                            INNER JOIN `grade_level_list` gl ON s.grade_level_id = gl.id
                                                            WHERE s.delete_flag = 0 AND s.status = 1 " . (isset($strand_id) ? " AND s.strand_id = '{$strand_id}'" : "") . "
                                                            ORDER BY s.name ASC");
                                    while($row = $section->fetch_assoc()):
                                    ?>
                                    <option value="<?= $row['id'] ?>" <?= isset($section_id) && $section_id == $row['id'] ? 'selected' : '' ?>>
                                        <?= $row['name'] . ' - ' . $row['grade_level_name'] ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <?php if (isset($_GET['id'])) : ?>
                                    <small class="text-info"><i>Leave this blank if you don't want to change the section.</i></small>
                                <?php endif; ?>
                            </div>
                            <!-- Student Status Dropdown -->
                            <div class="form-group col-md-2">
                                <label for="student_status_id" class="control-label">Classification</label>
                                <select name="student_status_id" id="student_status_id" class="form-control form-control-sm form-control-border" required>
                                    <option value="" disabled <?= !isset($student_status_id) ? "selected" : "" ?>></option>
                                    <?php 
                                    $studentStatusQuery = $conn->query("SELECT * FROM `student_status` ORDER BY `id` ASC");
                                    while($statusRow = $studentStatusQuery->fetch_assoc()):
                                    ?>
                                        <option value="<?= $statusRow['id'] ?>" <?= isset($student_status_id) && $student_status_id == $statusRow['id'] ? 'selected' : '' ?>><?= $statusRow['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
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
                                        while($subRow = $sections->fetch_assoc()):
                                            $sectionOptions[] = "{ id: " . $subRow['id'] . ", name: '" . $subRow['name'] . "', grade_level_name: '" . $subRow['grade_level_name'] . "' }";
                                        endwhile;
                                        echo implode(",", $sectionOptions);
                                        ?>
                                    ],
                                    <?php endwhile; ?>
                                };

                                // Update the section dropdown based on the selected strand
                                strandDropdown.on("change", updatesectionDropdown);

                                // Trigger the updatesectionDropdown function on page load
                                updatesectionDropdown();

                                function updatesectionDropdown() {
                                    var selectedStrand = strandDropdown.val();
                                    var availablesections = sectionsByStrand[selectedStrand];

                                    // Clear existing options
                                    sectionDropdown.empty();

                                    // Add a default option
                                    sectionDropdown.append("<option value='' disabled selected>Select section</option>");

                                    // Add new options based on the selected strand
                                    if (availablesections) {
                                        availablesections.forEach(function(section) {
                                            sectionDropdown.append(`<option value="${section.id}">${section.grade_level_name} - ${section.name}</option>`);
                                        });
                                    }
                                }
                            });
                            </script>
                            </script>
                        </div>
                        <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Personal Information:</H5>
                        <div class="row px-2">
                            <div class="form-group col-md-4">
                                <label for="firstname" class="control-label">First Name:</label>
                                <input type="text" name="firstname" id="firstname" value="<?= isset($firstname) ? ucwords($firstname) : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="middlename" class="control-label">Middle Name:</label>
                                <input type="text" name="middlename" id="middlename" value="<?= isset($middlename) ? ucwords($middlename) : "" ?>" class="form-control form-control-sm rounded-0" placeholder='Optional'>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lastname" class="control-label">Last Name:</label>
                                <input type="text" name="lastname" id="lastname" autofocus value="<?= isset($lastname) ? ucwords($lastname) : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                        </div>

                        <script>
                        function generateUsername() {
                            var roll = $("#roll").val(); // Get the value from the 'roll' input field

                            if (roll) {
                                // Remove any spaces and convert to lowercase
                                var sanitizedRoll = roll.replace(/\s/g, '').toLowerCase();
                                var username = sanitizedRoll; // Append '.aihsshs' to the roll
                                $("#username").val(username);
                            } else {
                                // Handle the case when the 'roll' field is empty
                            }
                        }
                        </script>

                        <div class="row px-2">
                                <div class="form-group col-md-2">
                                    <label for="gender" class="control-label">Sex:</label>
                                    <select name="gender" id="gender" value="<?= isset($gender) ? $gender : "" ?>" class="form-control form-control-sm rounded-0"  required>
                                        <option disabled selected value></option>
                                        <option <?= isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option <?= isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="dob" class="control-label">Date of Birth:</label>
                                    <input type="date" name="dob" id="dob" oninput="generatePasswordAndSetInput()" value="<?= isset($dob) ? $dob : "" ?>" class="form-control form-control-sm rounded-0" required>
                                </div>

                                <script>
                                    // Get the current date
                                    var currentDate = new Date();

                                    // Subtract 15 years from the current date
                                    currentDate.setFullYear(currentDate.getFullYear());

                                    // Format the date as YYYY-MM-DD
                                    var maxDate = currentDate.toISOString().split('T')[0];

                                    // Set the max attribute of the input element to 15 years ago from the current date
                                    document.getElementById('dob').setAttribute('max', maxDate);
                                </script>


                                <div class="form-group col-md-4">
                                    <label for="contact" class="control-label">Contact Number:</label>
                                    <input type="number" onkeydown="cnumber(this);" onkeyup="cnumber(this);" placeholder='09XXXXXXXXX' name="contact" id="contact" maxlength="11" value="<?= isset($contact) ? $contact : "" ?>" class="form-control form-control-sm rounded-0" required>
                                </div>
                                <script>
                                    function cnumber(element)
                                {
                                    var max_chars = 11;
                                        
                                    if(element.value.length > max_chars) {
                                        element.value = element.value.substr(0, max_chars);
                                        alert('You have reached a limit of 11 Digits');
                                    }
                                }
                                </script>
                            <div class="form-group col-md-4">
                                <label for="email_address" class="control-label">Email Address:</label>
                                <input type="text" name="email_address" id="email_address" class="form-control form-control-sm rounded-0" required value="<?= isset($email_address) ? $email_address : "" ?>">
                            </div>
                        </div>
                        <div class="row px-2">
                            <div class="form-group col-md-4">
                                <label for="guardian_name" class="control-label">Guardian Name:</label>
                                <input type="text" name="guardian_name" id="guardian_name" class="form-control form-control-sm rounded-0" placeholder='Optional'  value="<?= isset($guardian_name) ? $guardian_name : "" ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="guardian_contact" class="control-label">Guardian Contact Number:</label>
                                <input type="number" onkeydown="cnumber(this);" onkeyup="cnumber(this);" placeholder='Optional' name="guardian_contact" id="guardian_contact" maxlength="11" value="<?= isset($guardian_contact) ? $guardian_contact : "" ?>" class="form-control form-control-sm rounded-0" >
                            </div>
                                    <script>
                                        function cnumber(element)
                                    {
                                        var max_chars = 11;
                                            
                                        if(element.value.length > max_chars) {
                                            element.value = element.value.substr(0, max_chars);
                                            alert('You have reached a limit of 11 Digits');
                                        }
                                    }
                                    </script>
                        </div>
                        <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Portal Account:</H5>
                        <div class="row px-2">
                            <div class="form-group col-md-4">
                                <label for="username" class="control-label">Username:</label>
                                <input type="text" name="username" id="username" autofocus value="<?= isset($username) ? $username : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <script>
                                function generatePassword(dob) {
                                    var dobParts = dob.split('-');
                                    var day = dobParts[0];
                                    var month = dobParts[1];
                                    var year = dobParts[2];

                                    // Create a password by concatenating the year, month, and day
                                    var password = day + month + year;

                                    return password;
                                }

                                function generatePasswordAndSetInput() {
                                    var dob = document.getElementById("dob").value;
                                    var password = generatePassword(dob);
                                    document.getElementById("password").value = password;
                                }
                                </script>
                                <div class="form-group col-md-4">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-sm rounded-0" value="" autocomplete="off" <?= isset($_GET['id']) ? '' : 'required' ?>>
                                    <?php if (isset($_GET['id'])) : ?>
                                        <small class="text-info"><i>Leave this blank if you don't want to change the password.</i></small>
                                    <?php endif; ?>
                                </div>
                                <?php if (!isset($_GET['id']) && $activeSchoolYearId !== null) : ?>
                                    <input type="hidden" name="school_year_id" value="<?= $activeSchoolYearId ?>">
                                <?php endif; ?>
                            </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-flat btn-primary btn-sm" type="submit" form="student_form">Save Student Details</button>
            <a id="backButton" class="btn btn-default border btn-sm btn-flat"> Cancel</a>
                <script>
                    document.getElementById('backButton').addEventListener('click', function() {
                        window.history.back(); // Use JavaScript to navigate back
                    });
                </script>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#student_form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_student",
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
                        location.href="./?page=students/view_student&id="+resp.sid;
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
    function fetchSubjects() {
        var sectionId = $("#section_id").val();

        $.ajax({
            url: 'fetch_subjects.php', // Create a PHP file to handle this AJAX request
            method: 'POST',
            data: { section_id: sectionId },
            success: function(response) {
                // Parse the response and update the subject dropdown
                var subjects = JSON.parse(response);

                var subjectDropdown = $("#subject_id");
                subjectDropdown.empty();

                subjects.forEach(function(subject) {
                    subjectDropdown.append(`<option value="${subject.id}">${subject.name}</option>`);
                });
            },
            error: function(err) {
                console.log(err);
                // Handle the error, if any
            }
        });
    }

    // Event listener for section dropdown change
    $("#section_id").on("change", fetchSubjects);
</script>