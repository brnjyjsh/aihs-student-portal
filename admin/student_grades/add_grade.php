<?php
require_once('../../config.php');
if (isset($_GET['student_subject_id'])) {
    $student_subject_id = $_GET['student_subject_id'];
    $qry = $conn->query("SELECT * FROM `student_grade` where student_subject_id = '$student_subject_id'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    } else {
        echo "<center><small class='text-muted'>Unknown student_grade ID " . $_GET['student_subject_id'] . ".</small></center>";
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
    <form action="" id="grade-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="student_subject_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="grade" class="control-label">Grade</label>
                <input type="number" id="grade" name="grade" value="<?= isset($grade) ? $grade : '' ?>" class="form-control form-control-border form-control-sm" min="60" max="100" oninput="validateGrade(this)">
            </div>
        </div>
    </form>
    <script>
        function validateGrade(input) {
            const value = parseInt(input.value);

            if (isNaN(value) || value < 60 || value > 100 || input.value.length > 3) {
                input.value = input.value.slice(0, 3); // Limit input to 3 characters
                input.setCustomValidity("Value must be a number between 60 and 100");
                input.reportValidity();
            } else {
                input.setCustomValidity("");
            }
        }
    </script>
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
        })
        $('#uni_modal #grade-form').submit(function(e){
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
                url:_base_url_+"classes/Master.php?f=save_grade",
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