<?php require_once('../config.php') ?>
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    extract($_POST);

    if ($new_password !== $confirm_password) {
        $error = "Password does not match.";
    } else {
        $uid = $_GET['uid'] ?? "";
        $stmt = $conn->prepare("SELECT * FROM `student_list` WHERE md5(`id`) = ?");
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $id = $data['id'];

            // Use a secure hashing algorithm like password_hash
            $hashed_password = md5($new_password);

            // Update the password for the specific user
            $update_stmt = $conn->prepare("UPDATE `student_list` SET `password` = ? WHERE `id` = ?");
            $update_stmt->bind_param('si', $hashed_password, $id);

            if ($update_stmt->execute()) {
                $_SESSION['msg']['success'] = "New Password has been saved successfully.";
                header('location: ../login.php');
                exit;
            } else {
                $error = 'Password has failed to update.';
            }
        } else {
            $error = "User is not registered on this website.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('../inc/header-reset.php') ?>
<body class="hold-transition ">
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
    <div class="d-flex align-items-center" id="login">
    <div class="col-sm-12 col-md-6 col-lg-3">
        <div class="rounded-0">
            <center><img src="..\uploads\logoflat.png" style="margin-top: 25px;" alt="" id="logo-img"></center>
        </div>
    </div>
</div>
<div class="h-0 d-flex align-items-center w-100" id="login">
    <div class="col-7 h-25 d-flex align-items-center justify-content-center">
    </div>
    <div class="col-5 h-5 bg-gradient">
        <div class="d-flex w-100 h-100 justify-content-center align-items-center">
            <div class="card col-sm-12 col-md-6 col-lg-3 card-outline card-yellow rounded-1 shadow box">
                    <div class="card-body">
                        <br>
                        <div class="text-muted"><small><em>Please Fill all the required fields</em></small></div>
                        <BR>
                            <?php if(isset($error) && !empty($error)): ?>
                                <div class="message-error"><?= $error ?></div>
                            <?php endif; ?>
                            <?php if(isset($_SESSION['msg']['success']) && !empty($_SESSION['msg']['success'])): ?>
                            <div class="message-success">
                                <?php 
                                    echo $_SESSION['msg']['success'];
                                    unset($_SESSION['msg']);
                                ?>
                            </div> 
                            <?php endif; ?> 
                                <div id="login-wrapper">
                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-lg-12"> 
                                                <div class="input-field form-group">
                                                    <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control form-control-border" value="<?= $_POST['new_password'] ?? "" ?>" autofocus required="required">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="input-field form-group">
                                                    <div class="password-input">
                                                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-control form-control-border" value="<?= $_POST['confirm_password'] ?? "" ?>" required="required">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                                <div class="col-lg-13">
                                                    <div class="form-group text-right">
                                                    <button type="submit" class="reset-btn btn h-auto w-100 btn-warning btn-block btn-flat text-navy">Reset Password</button>
                                                    <br>
                                                </div>
                                    </form>   
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url ?>../plugins/select2/js/select2.full.min.js"></script>
</body>
</html>
