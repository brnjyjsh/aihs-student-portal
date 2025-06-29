<?php
require_once('../../config.php');
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM `school_year_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        $res = $qry->fetch_array();
        foreach ($res as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
}

// Set all records to '0' (inactive) in the database
$conn->query("UPDATE `school_year_list` SET status = 0");

// Now, update the desired record to '1' (active)
if (isset($_GET['id'])) {
    $conn->query("UPDATE `school_year_list` SET status = 1 WHERE id = '{$_GET['id']}'");

    // Retrieve the newly active school year ID
    $active_school_year_id = $_GET['id'];

    // Update school_year_id in the trsubject table
    $conn->query("UPDATE `trsubject` SET school_year_id = '{$active_school_year_id}'");
}

?>
<style>
    img#cimg {
        height: 17vh;
        width: 25vw;
        object-fit: scale-down;
    }
</style>
<div class="container-fluid">
    <form action="" id="school_year-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group">
            <label for="name" class="control-label">School Year</label>
            <input type="text" name="name" id="name" class="form-control form-control-border" placeholder="Enter (From) to (To)" value="<?php echo isset($name) ? $name : '' ?>" required>
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
            $('#status').select2({
                placeholder:'Please Select Here',
                width:'100%',
                dropdownParent:$('#uni_modal')
            })
        })
        $('#uni_modal #school_year-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_school_year",
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
