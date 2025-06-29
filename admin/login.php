<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
 <?php require_once('inc/header.php') ?>
<body class="hold-transition ">
  <script>
    start_loader()
  </script>
  <div class="h-0 d-flex align-items-center w-100" id="login">
        <div class="col-5 h-5">
          <div class="d-flex w-100 h-100 justify-content-center align-items-center">
            <div class="col-sm-12 col-md-6 col-lg-3">
              <div class="rounded-0">
                <center><img src="../uploads/logoflat-admin.png" style="margin-top: 25px;" alt="" id="logo-img"></center>
              </div>
            </div>
          </div>
        </div>
  </div>
  <div class="h-0 d-flex align-items-center w-100" id="login">
      <div class="col-7 h-25 d-flex align-items-center justify-content-center">
      </div>
        <div class="col-5 h-5 bg-gradient">
          <div class="d-flex w-100 h-100 justify-content-center align-items-center">
            <div class="card col-sm-12 col-md-6 col-lg-3 card-outline card-yellow rounded-1 shadow box">
              <br>
              <div class="card-body rounded-0">
                <form id="login-frm" action="" method="post">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control border-1" autofocus name="username" placeholder="Username">
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-user"></span>
                      </div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-13">
                      <button type="submit" class="btn h-auto w-100 btn-warning btn-block btn-flat text-navy">Log In</button>
                    
                      <br>
                      <center><p style="font-weight: normal; color: white; font-family: sans-serif; font-size: 14px;" >Log in as <a href="../teacher_login.php" style="color: #f4a410; text-decoration: none;">Teacher</a> or <a href="../login.php" style="color: #f4a410; text-decoration: none;">Student</a>
                                        </center>
                    </div>
                  <div class="row">
                    <div class="col-8">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>

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
  </style>
  
<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function(){
    end_loader();
  })
</script>
</body>
</html>