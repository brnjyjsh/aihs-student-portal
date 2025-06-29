</style>
<!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand bg-navy">
        <!-- Brand Logo -->
        <!--<a href="<?php echo base_url ?>admin" class="brand-link bg-transparent text-sm border-0 shadow-sm">
        <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 1.8rem;height: 1.8rem;max-height: unset;object-fit:scale-down;object-position:center center">
        <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
        </a>-->
        <a class="brand-link bg-transparent text-sm border-0 shadow-sm">
        <img src="..\uploads\logoflat.png" alt="" id="logo-img" class="brand-image" 
        style=
        "width: 12.5rem;
        height: 5rem;
        max-height: unset;
        object-fit:scale-down;
        object-position:center center;
        pointer-events: none;">
        </a>
        <!-- Sidebar -->
        <br><br>
        <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
          <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
          </div>
          <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
          </div>
          <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
          <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
              <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                <!-- Sidebar user panel (optional) -->
                <div class="clearfix"></div>
                <!-- Sidebar Menu -->
                <nav class="mt-4">
                   <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
                   <?php if($_settings->userdata('type') == 1): ?>
                    <li class="nav-item dropdown">
                      <a href="./" class="nav-link nav-home">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                          Dashboard
                        </p>
                      </a>
                    </li>
                    <li class="nav-header">Account Management</li>
                    <!--<li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=students/manage_student" class="nav-link nav-students_manage_student">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                          New Student
                        </p>
                      </a>
                    </li>-->
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=students" class="nav-link nav-students">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                          Student List
                        </p>
                      </a>
                    </li>
                    <!--<li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=teachers/manage_teacher" class="nav-link nav-teachers_manage_teacher">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>
                          New Teacher
                        </p>
                      </a>
                    </li>-->
                    <li class="nav-item">
                      <a href="<?php echo base_url ?>admin/?page=teachers" class="nav-link nav-teachers">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p>
                          Teacher List
                        </p>
                      </a>
                    </li>
                    <li class="nav-header">Grades Management</li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=student_grades" class="nav-link nav-student_grades">
                        <i class="nav-icon fas fa-award"></i>
                        <p>
                          Grades
                        </p>
                      </a>
                    </li>
                    <li class="nav-header">Maintenance</li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=school_year" class="nav-link nav-school_year">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                          School Year
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=quarters" class="nav-link nav-quarters">
                        <i class="nav-icon fas fa-clone"></i>
                        <p>
                          Quarter
                        </p>
                      </a>
                    </li>
                    <!--<li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=semesters" class="nav-link nav-semesters">
                        <i class="nav-icon far fa-calendar"></i>
                        <p>
                          Semester
                        </p>
                      </a>
                    </li>-->
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=grade_level" class="nav-link nav-grade_level">
                        <i class="nav-icon fas fa-school"></i>
                        <p>
                          Grade Level
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=strands" class="nav-link nav-strands">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>
                          Strand/Track List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=subjects" class="nav-link nav-subjects">
                        <i class="nav-icon fas fa-scroll"></i>
                        <p>
                         Subject List
                        </p>
                      </a>
                    </li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=sections" class="nav-link nav-sections">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>
                         Section List
                        </p>
                      </a>
                    </li>
                    <!--<li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                          Admin User
                        </p>
                      </a>
                    </li>-->
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                          System Information
                        </p>
                      </a>
                    </li>
                    <?php endif; ?>
                  </ul>
                </nav>
                <!-- /.sidebar-menu -->
              </div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar-corner"></div>
        </div>
        <!-- /.sidebar -->
      </aside>
      <script>
        var page;
    $(document).ready(function(){
      page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      page = page.replace(/\//gi,'_');

      if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
        if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
          $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

      }
      
		$('#receive-nav').click(function(){
      $('#uni_modal').on('shown.bs.modal',function(){
        $('#find-student [name="tracking_code"]').focus();
      })
			uni_modal("Enter Tracking Number","student/find_student.php");
		})
    })
  </script>