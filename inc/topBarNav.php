<style>
  .user-img{
        position: absolute;
        height: 27px;
        width: 27px;
        object-fit: cover;
        left: -7%;
        top: -12%;
  }
  .btn-rounded{
        border-radius: 50px;
  }
</style>
<!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-light border-top-0  border-left-0 border-right-0 text-sm shadow-sm bg-gradient-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 1.8rem;height: 1.8rem;max-height: unset;object-fit:scale-down;object-position:center center">
          <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo base_url ?>" class="nav-link"><b><?php echo $_settings->info('short_name') ?></b></a>
          </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <!-- Navbar Search -->
          <!-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
              <form class="form-inline">
                <div class="input-group input-group-sm">
                  <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                    <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li> -->
          <!-- Messages Dropdown Menu -->
          <li class="nav-item">
            <div class="btn-group nav-link">
                  <!--<button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
                    <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button> -->
                  <?php if($_settings->userdata('id') > 3): ?>
                  <span class="mx-2" style="font-size: 15px;">Hi, <b><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></b></span>
                  <?php if($_settings->userdata('type') == 3): ?>
                  <span class="mx-1" ><a href="<?= base_url.'classes/Login.php?f=student_logout' ?>"><i class="fas fa-sign-out-alt" style="color: grey; font-size: 15px;"></i></a></span>
                  <?php endif; ?>
                  <?php if($_settings->userdata('type') == 2): ?>
                  <span class="mx-1" ><a href="<?= base_url.'classes/Login.php?f=teacher_logout' ?>"><i class="fas fa-sign-out-alt" style="color: grey; font-size: 15px;"></i></a></span>
                  <?php endif; ?>
                <?php else: ?>
                  <a href="./register.php" class="mx-2 text-light me-2">Register</a>
                  <a href="./login.php" class="mx-2 text-light me-2">Student Login</a>
                  <a href="./admin" class="mx-2 text-light">Admin login</a>
                  <span class="mx-1" ><a href="<?= base_url.'classes/Login.php?f=student_logout' ?>"><i class="fas fa-sign-out-alt" style="color: grey; font-size: 15px;"></i></a></span>
                <?php endif; ?>
                
              </div>
          </li>
          <li class="nav-item">
            
          </li>
         <!--  <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
            </a>
          </li> -->
        </ul>
      </nav>
      <!-- /.navbar -->