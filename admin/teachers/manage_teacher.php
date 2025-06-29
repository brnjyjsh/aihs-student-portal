<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `teacher_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}

// Add a value of 'type' in the teacher_list table
if (!isset($type)) {
    $type = 2; // You can modify this value as needed
}
?>
<div class="content py-2">
    <div class="card card-outline card-navy shadow rounded-0">
        <div class="card-header">
            <h3 class="card-title"><b><?= isset($id) ? "Update Teacher Details - ". $roll : "Add New Teacher" ?></b></h3>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form action="" id="teacher_form">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <fieldset class="border-bottom">
                    <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px;">Academic Details </H5>
                        <div class="row px-2">
                            <div class="form-group col-md-4">
                                <label for="roll" class="control-label">Employee ID:</label>
                                <input type="number" onkeydown="lrn(this);" onkeyup="lrn(this);" name="roll" id="roll" maxlength="12" autofocus value="<?= isset($roll) ? $roll : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                        </div>
                        <script>
                            function lrn(element)
                        {
                            var max_chars = 7;
                                
                            if(element.value.length > max_chars) {
                                element.value = element.value.substr(0, max_chars);
                                {
                                alert("You have reached a limit of 7 Digits");
                                }
                            }
                        }
                        </script>
                        <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Personal Information:</H5>
                        <div class="row px-2">
                            <div class="form-group col-md-4">
                                <label for="firstname" class="control-label">First Name:</label>
                                <input type="text" name="firstname" id="firstname" oninput="generateUsername()" value="<?= isset($firstname) ? $firstname : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="middlename" class="control-label">Middle Name:</label>
                                <input type="text" name="middlename" id="middlename" value="<?= isset($middlename) ? $middlename : "" ?>" class="form-control form-control-sm rounded-0" placeholder='Optional'>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lastname" class="control-label">Last Name:</label>
                                <input type="text" name="lastname" id="lastname" oninput="generateUsername()" value="<?= isset($lastname) ? $lastname : "" ?>" class="form-control form-control-sm rounded-0" required>
                            </div>
                        </div>
                        <div class="row px-2">
                        <script>
                            function generateUsername() {
                                var firstName = $("#firstname").val();
                                var lastName = $("#lastname").val();

                                if (firstName && lastName) {
                                    // Split the first name into words
                                    var words = firstName.split(' ');

                                    // Initialize an empty string for the first letters of each word
                                    var firstLetters = '';

                                    // Iterate through the words and extract the first letter of each
                                    for (var i = 0; i < words.length; i++) {
                                        if (words[i]) {
                                            firstLetters += words[i][0].toLowerCase(); // Get the first letter and convert to lowercase
                                        }
                                    }

                                    var lastInitial = lastName.toLowerCase().trim();
                                    var username = firstLetters + "." + lastInitial + "." + "aihsshs";
                                    username = username.replace(/\s/g, ''); // Remove spaces from the username using a regular expression
                                    $("#username").val(username);
                                } else {
                                    // Handle the case when first name or last name is empty
                                }
                            }

                                    /*
                                    var firstInitial = firstName.charAt(0).toLowerCase();
                                    var lastInitial = lastName.charAt(0).toLowerCase();
                                    var username = firstInitial + lastInitial + "aihsshs";
                                    */
                        </script>
                                <div class="form-group col-md-2">
                                    <label for="gender" class="control-label">Sex:</label>
                                    <select name="gender" id="gender" value="<?= isset($gender) ? $gender : "" ?>" class="form-control form-control-sm rounded-0" required>
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
                            <H5 class="bg-gradient-navy text-light py-2 px-2" style="margin-bottom: 20px; border-radius: 5px; margin-top: 20px;">Portal Account:</H5>
                            <div class="row px-2">
                                <div class="form-group col-md-4">
                                    <label for="username" class="control-label">Username:</label>
                                    <input type="text" name="username" id="username" autofocus value="<?= isset($username) ? $username : "" ?>" class="form-control form-control-sm rounded-0" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="password">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-sm rounded-0" value="" autocomplete="off" <?= isset($_GET['id']) ? '' : 'required' ?>>
                                    <?php if (isset($_GET['id'])) : ?>
                                        <small class="text-info"><i>Leave this blank if you don't want to change the password.</i></small>
                                    <?php endif; ?>
                                </div>

                            </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-flat btn-primary btn-sm" type="submit" form="teacher_form">Save teacher Details</button>
            <a href="./?page=teachers" class="btn btn-flat btn-default border btn-sm">Cancel</a>
        </div>
    </div>
</div>
<script>
    $(function () {
    $('#teacher_form').submit(function (e) {
        e.preventDefault();
        var _this = $(this);
        $('.pop-msg').remove();
        var el = $('<div>');
        el.addClass("pop-msg alert");
        el.hide();
        start_loader();
        
        // Additional code to set the 'type' value
        var formData = new FormData($(this)[0]);
        formData.append('type', 2); // Adjust the value as needed
        
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_teacher",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("An error occurred", 'error');
                end_loader();
            },
            success: function (resp) {
                if (resp.status == 'success') {
                    location.href = "./?page=teachers/view_teacher&id=" + resp.sid;
                } else if (!!resp.msg) {
                    el.addClass("alert-danger");
                    el.text(resp.msg);
                    _this.prepend(el);
                } else {
                    el.addClass("alert-danger");
                    el.text("An error occurred due to an unknown reason.");
                    _this.prepend(el);
                }
                el.show('slow');
                $('html,body,.modal').animate({
                    scrollTop: 0
                }, 'fast');
                end_loader();
            }
        });
    });
});
</script>