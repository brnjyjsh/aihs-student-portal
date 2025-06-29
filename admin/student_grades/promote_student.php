<?php
require_once('../../config.php');
if(isset($_GET['section_id'])) {
    $sid = $_GET['section_id'];
    $qry = $conn->query("SELECT * FROM `section_list` where id = '$sid'");
    if($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach($res as $k => $v) {
            if(!is_numeric($k)) 
            $$k = $v;
        }
    } else {
        echo "<center><small class='text-muted'>Unknown Section ID.</small></center>";
        exit;
    }
} else {
    echo "<center><small class='text-muted'>Section ID is required.</small></center>";
    exit;
}

// Retrieve school_year_id from session
$school_year_id = $_SESSION['school_year_id'] ?? '';

?>
<?php
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
<style>
	img#cimg{
		height: 17vh;
		width: 25vw;
		object-fit: scale-down;
	}
</style>
<div class="container-fluid">
    <form action="" id="promote-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="sy" value="<?php echo $activeSchoolYearID ?>">
        <!-- section Dropdown -->
<div class="form-group col-md-12">
    <label for="section_id" class="control-label">Section</label>
    <select name="section_id" id="section_id" class="form-control form-control-sm form-control-border">
        <option value="" disabled <?= !isset($section_id) ? "selected" : "" ?>></option>
        <?php 
        $section = $conn->query("SELECT s.*, gl.name as grade_level_name
                                FROM `section_list` s
                                INNER JOIN `grade_level_list` gl ON s.grade_level_id = gl.id
                                WHERE s.delete_flag = 0 AND s.status = 1 AND s.id != $sid" . 
                                (isset($strand_id) ? " AND s.strand_id = '{$strand_id}'" : "") . 
                                " AND s.grade_level_id != 1  -- Exclude records with grade_level_id = 1
                                ORDER BY s.name ASC");

        while($row = $section->fetch_assoc()):
        ?>
            <option value="<?= $row['id'] ?>" <?= isset($section_id) && $section_id == $row['id'] ? 'selected' : '' ?>>
                <?= $row['name'] . ' - ' . $row['grade_level_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>


        <!-- School Year Dropdown -->
        <div class="form-group col-md-12">
           <label for="school_year_id" class="control-label">School Year</label>
                <select name="school_year_id" id="school_year_id" class="form-control form-control-sm form-control-border">
                    <option value="" disabled <?= !isset($school_year_id) ? "selected" : "" ?>></option>
                    <?php 
                   $school_year_list = $conn->query("SELECT s.*
                   FROM `school_year_list` s
                   WHERE s.delete_flag = 0
                   AND s.id > '$activeSchoolYearID'
                   ORDER BY s.id ASC
                   LIMIT 1");

                        while($row = $school_year_list->fetch_assoc()):
                    ?>
                    <option value="<?= $row['id'] ?>" <?= isset($school_year_id) && $school_year_id == $row['id'] ? 'selected' : '' ?>>
                        <?= $row['name']?>
                    </option>
                <?php endwhile; ?>
                </select>
        </div>     
    </form>
</div>
<script>
   $(function () {
    $('#uni_modal').on('shown.bs.modal', function () {
        $('#amount').focus();
        $('#section_id').select2({
            placeholder: 'Please Select Here',
            width: "100%",
            dropdownParent: $('#uni_modal')
        })
        $('#school_year_id').select2({
            placeholder: 'Please Select Here',
            width: "100%",
            dropdownParent: $('#uni_modal')
        })
    })

    $('#uni_modal #promote-form').submit(function (e) {
        e.preventDefault();
        var _this = $(this)
        if (_this[0].checkValidity() == false) {
            _this[0].reportValidity();
            return false;
        }
        $('.pop-msg').remove()
        var el = $('<div>')
        el.addClass("pop-msg alert")
        el.hide()
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=promote_student",
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err)
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function (resp) {
    console.log(resp); // Log the entire response object for debugging

    if (resp.status == 'success') {
        location.reload();
    } else if (resp.msg) {
        el.addClass("alert-danger");
        el.text(resp.msg);
        _this.prepend(el);
        el.show('slow');
        $('html,body,.modal').animate({ scrollTop: 0 }, 'fast');
        end_loader();
    } else {
        console.log("Unknown error occurred. Response:", resp); // Log the response object for further inspection
        el.addClass("alert-danger");
        el.text("An error occurred due to an unknown reason.");
        _this.prepend(el);
        el.show('slow');
        $('html,body,.modal').animate({ scrollTop: 0 }, 'fast');
        end_loader();
    }

    // Check if there are students with missing grades by making an AJAX request to check_student_grades.php
    $.ajax({
        url: '../../path/to/check_student_grades.php?id=' + resp.section_id, // Adjust the path as needed
        dataType: 'json',
        success: function (response) {
            if (response.has_missing_grades) {
                var alertMessage = "Promotion successful, but some students in the section have missing grades. Check and update their grades.";
                alert(alertMessage);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error checking student grades:", error);
        }
    });
}

        })
    })
})

</script>