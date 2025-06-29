<?php
$user = $conn->query("SELECT s.*, d.name as strand, c.name as section,
    CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname 
    FROM student_list s
    INNER JOIN strand_list d ON s.strand_id = d.id
    INNER JOIN section_list c ON s.section_id = c.id
    WHERE s.id = '{$_settings->userdata('id')}'");

foreach($user->fetch_array() as $k =>$v){
    $$k = $v;
}
?>
<style>
    .student-img{
		object-fit:scale-down;
		object-position:center center;
        height:200px;
        width:200px;
	}
</style>
<div class="content col-lg-12">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title" style="font-weight: bold;">Update Details</h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="update-form">
                    <input type="hidden" name="id" value="<?= $_settings->userdata('id') ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email_address" class="control-label text-navy">Email</label>
                                <input type="email" name="email_address" id="email_address" placeholder="Email" class="form-control form-control-border" required value="<?= isset($email_address) ?$email_address : "" ?>">
                            </div>
                            <div class="form-group">
                                <label for="contact" class="control-label text-navy">Contact Number</label>
                                <input type="number" onkeydown="cnumber(this);" onkeyup="cnumber(this);" name="contact" id="contact" placeholder="Contact Number" class="form-control form-control-border" required value="<?= isset($contact) ?$contact : "" ?>">
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
                            <div class="form-group">
                                <label for="guardian_name" class="control-label text-navy">Guardian Name</label>
                                <input type="text" name="guardian_name" id="guardian_name" placeholder="Guardian Name" class="form-control form-control-border" required value="<?= isset($guardian_name) ?$guardian_name : "" ?>">
                            </div>
                            <div class="form-group">
                                <label for="guardian_contact" class="control-label text-navy">Guardian Contact Number</label>
                                <input type="number" onkeydown="cnumber(this);" onkeyup="cnumber(this);" name="guardian_contact" id="guardian_contact" placeholder="Guardian Contact Number" class="form-control form-control-border" required value="<?= isset($guardian_contact) ?$guardian_contact : "" ?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label text-navy">New Password</label>
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-border">
                            </div>
                            <div class="form-group">
                                <label for="cpassword" class="control-label text-navy">Confirm New Password</label>
                                <input type="password" id="cpassword" placeholder="Confirm Password" class="form-control form-control-border">
                            </div>
                            <small class='text-muted'>Leave the New Password and Confirm New Password Blank if you don't wish to change your password.</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="oldpassword">Please Enter your Current Password</label>
                                <input type="password" name="oldpassword" id="oldpassword" placeholder="Current Password" class="form-control form-control-border" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <button class="btn btn-default bg-navy btn-flat"> Update</button>
                                <a href="./?page=profile" class="btn btn-light border btn-flat"> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
	}
    $(function(){
        // Update Form Submit
        $('#update-form').submit(function(e){
            e.preventDefault()
            var _this = $(this)
                $(".pop-msg").remove()
                $('#password, #cpassword').removeClass("is-invalid")
            var el = $("<div>")
                el.addClass("alert pop-msg my-2")
                el.hide()
            if($("#password").val() != $("#cpassword").val()){
                el.addClass("alert-danger")
                el.text("Password does not match.")
                $('#password, #cpassword').addClass("is-invalid")
                $('#cpassword').after(el)
                el.show('slow')
                return false;
            }
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=save_student",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType:'json',
                error:err=>{
                    console.log(err)
                    el.text("An error occured while saving the data")
                    el.addClass("alert-danger")
                    _this.prepend(el)
                    el.show('slow')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href= "./?page=profile"
                    }else if(!!resp.msg){
                        el.text(resp.msg)
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }else{
                        el.text("An error occured while saving the data")
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0},'fast')
                }
            })
        })
    })
</script>