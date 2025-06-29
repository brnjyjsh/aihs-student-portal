<?php require_once('./config.php') ?>
<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    extract($_POST);
    $stmt = $conn->prepare("SELECT * FROM `student_list` where `username` = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $data = $result->fetch_assoc();
        $email_address = $data['email_address'];

        $subject = "Amadeo Integrated High School Student Portal - Reset Password";
        $message = "";
        ob_start();
        include("reset_mail-template.php");
        $message = ob_get_clean();
        // echo $message;exit;
        $eol = "\r\n";
        // Mail Main Header
        $headers = "From: info@sample.com" . $eol;
        $headers .= "Reply-To: noreply@sample.com" . $eol;
        $headers .= "To: <{$email_address}>" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: text/html; charset=iso-8859-1" . $eol;
        try{
            mail($email_address, $subject, $message, $headers);
            $_SESSION['msg']['success'] = "We have sent you an email to reset your password.";
            header('location: ./');
            exit;
        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
            exit;
        }
        ?>
        <?php
    }else{
        $error = "Student LRN is not registered.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body class="hold-transition ">
  <script>
    start_loader()
  </script>
  <style>
    html, body{
      height:calc(100%) !important;
      width:calc(100%) !important;
    }
    body{
      background-image: url("<?php echo validate_image($_settings->info('cover')) ?>");
      background-size:cover;
      background-repeat:no-repeat;
    }
    .login-title{
      text-shadow: 2px 2px black
    }
    #login{
      flex-direction:column !important
    }
    #login{
        direction:rtl
    }
    #login > *{
        direction:ltr
    }
    #logo-img{
        height:100%;
        width:100%;
        object-fit:scale-down;
        object-position:center center;
        border-radius:100%;
        pointer-events: none;
    }
    #login .col-7,#login .col-5{
      width: 100% !important;
      max-width:unset !important
    }
    .box
    {
      background-color: #00426a; 
        border-radius: 35px;
        border:2px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 12px 40px 0 rgba(0, 0, 0, 0.19);
    }
    .password-input {
    position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 55%;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .toggle-password i {
        font-size: 20px;
    }
    /* Set height of body and the document to 100% to enable "full page tabs" */
    body, html {
    height: 100%;
    margin: 0;
    font-family: Arial;
    outline: none;
    }

    /* Style tab links */
    .tablink {
    background-color: #555;
    color: white;
    float: center;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 10px 15px;
    font-size: 17px;
    width: 48%;
    }

    .tablink:hover {
    background-color: #777;
    outline: none;
    border: none;
    outline: none;
    }

    /* Style the tab content (and add height:100% for full page content) */
    .tabcontent {
    color: white;
    display: none;
    border: none;
    outline: none;
    padding: 25px 25px 10px 10px;
    height: auto;
    animation: fadeEffect 1s; /* Fading effect takes 1 second */
    }

    /* Go from zero to full opacity */
    @keyframes fadeEffect {
    from {opacity: 0;}
    to {opacity: 1;}
    }
    .centered {
    text-align: center;
    width: 100%;
    }

    #slogin {background-color: #00426a;}
    #recover {background-color: #00426a;}
  </style>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
    header('location: ./');
</script>
<?php endif;?> 
<div class="d-flex align-items-center" id="login">
    <div class="col-sm-12 col-md-6 col-lg-3">
        <div class="rounded-0">
            <center><img src="uploads\logoflat.png" style="margin-top: 25px;" alt="" id="logo-img"></center>
        </div>
    </div>
</div>
<div class="h-0 d-flex align-items-center w-100" id="login">
    <div class="col-7 h-25 d-flex align-items-center justify-content-center">
    </div>
    <div class="col-5 h-5 bg-gradient">
        <div class="d-flex w-100 h-100 justify-content-center align-items-center">
            <div class="card col-sm-12 col-md-6 col-lg-3 card-outline card-yellow rounded-1 shadow box">
                <!--<div class="card-header rounded-0">
                    <center><img src="uploads\logoflat.png" style="margin-bottom: -15px;" alt="" id="logo-img"></center>
                </div>-->
                    <div class="card-body">
                        <br>
                        <button class="tablink" onclick="openPage('slogin', this, '#FFC107')"  id="defaultOpen"><a class="text-navy">Log In</a></button>
                        <button class="tablink" onclick="openPage('recover', this, '#FFC107')"><a class="text-navy">Forgot Pasword</a></button>
                            <div id="slogin" class="tabcontent"> <!--LOG IN TAB-->
                                <form action="" id="slogin-form">
                                <?php if(isset($_SESSION['msg']['success']) && !empty($_SESSION['msg']['success'])): ?>
                                            <div class="message-success bg-success centered rounded-1">
                                                <?php 
                                                echo $_SESSION['msg']['success'];
                                                unset($_SESSION['msg']);
                                                ?>
                                            </div><br> 
                                        <?php endif; ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="text" name="username" id="username" placeholder="Username" class="form-control form-control-border" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <div class="password-input">
                                                        <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-border" required>
                                                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                                                        <i class="fas fa-eye"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-13">
                                            <div class="form-group text-right">
                                            <button type="submit" class="btn h-auto w-100 btn-warning btn-block btn-flat text-navy">Log In</button>
                                            <!--<br>
                                            <center><p style="font-weight: normal; color: white; font-family: sans-serif; font-size: 14px;" >Log in as <a href="teacher_login.php" style="color: #f4a410; text-decoration: none;">Teacher</a> or <a href="admin/login.php" style="color: #f4a410; text-decoration: none;">Admin</a></center>-->
                                            </div>
                                        </div>
                                </form>
                            </div>
                        
                            <div id="recover" class="tabcontent">
                                <div id="login-wrapper">
                                    <div class="text-muted"><small><em>Please Fill all the required fields</em></small></div>
                                    <?php if(isset($error) && !empty($error)): ?>
                                        <div class="message-error bg-red centered"><?= $error ?></div>
                                    <?php endif; ?>
                                    <form action="" method="POST">
                                        <div class="input-field">
                                            <input type="text" id="username" name="username" placeholder="Username" class="form-control form-control-border" value="<?= $_POST['username'] ?? "" ?>" required="required">
                                        </div>
                                        <div class="input-field "> <br>
                                        <button class="login-btn btn h-auto w-100 btn-warning btn-block btn-flat text-navy">Reset Password</button>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var toggleButton = document.querySelector(".toggle-password");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            passwordInput.type = "password";
            toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }
</script>

<script>
  $(document).ready(function(){
    end_loader();
    // Registration Form Submit
    $('#slogin-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
            $(".pop-msg").remove()
            $('#password, #cpassword').removeClass("is-invalid")
        var el = $("<div>")
            el.addClass("alert pop-msg my-2")
            el.hide()
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Login.php?f=user_login",
            method:'POST',
            data:_this.serialize(),
            dataType:'json',
            error:err=>{
                console.log(err)
                el.text("An error occured while saving the data")
                el.addClass("alert-danger")
                _this.prepend(el)
                el.show('slow')
                end_loader();
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.href= "./"
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

                    function openPage(pageName, elmnt, color) {
                    // Hide all elements with class="tabcontent" by default */
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }

                    // Remove the background color of all tablinks/buttons
                    tablinks = document.getElementsByClassName("tablink");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].style.backgroundColor = "";
                    }

                    // Show the specific tab content
                    document.getElementById(pageName).style.display = "block";

                    // Add the specific color to the button used to open the tab content
                    elmnt.style.backgroundColor = color;
                    }

                    // Get the element with id="defaultOpen" and click on it
                    document.getElementById("defaultOpen").click();
</script>
</body>
</html>