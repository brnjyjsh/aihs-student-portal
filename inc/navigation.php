<?php
$userID = $_settings->userdata('id');
$userQuery = $conn->prepare("SELECT s.*, d.name as strand, c.name as section, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname FROM student_list s INNER JOIN strand_list d ON s.strand_id = d.id INNER JOIN section_list c ON s.section_id = c.id WHERE s.id = ?");
$userQuery->bind_param("i", $userID);
$userQuery->execute();

if ($userQuery) {
    $userData = $userQuery->get_result()->fetch_assoc();

    if ($userData) {
        foreach ($userData as $k => $v) {
            $$k = $v;
        }

        // Check if the section_id has grade_level_id = 2
        $grade12SectionQuery = $conn->prepare("SELECT * FROM section_list WHERE id = ? AND grade_level_id = 2");
        $grade12SectionQuery->bind_param("i", $section_id);
        $grade12SectionQuery->execute();
        $grade12SectionResult = $grade12SectionQuery->get_result();
        $isGrade12Section = $grade12SectionResult->num_rows > 0;

    } else {
        // Handle the case when no user data is found
    }
} else {
    // Handle the case when there's an issue with the database query
}

?>

<!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand bg-navy">
        <!-- Brand Logo -->
        <a class="brand-link bg-transparent text-sm border-0 shadow-sm">
        <img src="uploads\logoflat.png" alt="" id="logo-img" class="brand-image" 
        style=
        "width: 12.5rem;
        height: 5rem;
        max-height: unset;
        object-fit:scale-down;
        object-position:center center;
        pointer-events: none;">
        </a>
        <!-- Sidebar -->
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
                <br><br>
                <nav class="mt-4">
                   <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item dropdown">
                      <a href="./" class="nav-link nav-home">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                          Home
                        </p>
                      </a>
                    </li>
                    <?php if($_settings->userdata('type') == 3): ?>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=profile" class="nav-link nav-profile">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                          Account
                        </p>
                      </a>
                    </li>
                    <?php endif; ?>
                    <?php if($_settings->userdata('type') == 2): ?>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=teacher_profile" class="nav-link nav-teacher_profile">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                          Teacher Account
                        </p>
                      </a>
                    </li>
                    <?php endif; ?>
                    <?php if($_settings->userdata('type') == 3): ?>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=subjects" class="nav-link nav-subjects">
                        <i class="nav-icon fas fa-scroll"></i>
                        <p>
                          Subjects
                        </p>
                      </a>
                    </li>
                    <li class="nav-header">My Grades</li>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=portal_grades_g11" class="nav-link nav-portal_grades_g11">
                        <i class="nav-icon fas fa-award"></i>
                        <p>
                          Grade 11
                        </p>
                      </a>
                    </li>
                    <?php if ($isGrade12Section): ?>
                    <li class="nav-item dropdown">
                        <a href="<?php echo base_url ?>?page=portal_grades_g12" class="nav-link nav-portal_grades_g12">
                            <i class="nav-icon fas fa-award"></i>
                            <p>
                                Grade 12
                            </p>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if($_settings->userdata('type') == 2): ?>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=assigned_subject" class="nav-link nav-assigned_subject">
                        <i class="nav-icon fas fa-scroll"></i>
                        <p>
                          Assigned Subject
                        </p>
                      </a>
                    </li>
                    <li class="nav-header">Grade Management</li>
                    <!--<li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=all_students" class="nav-link nav-all_students">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                          Masterlist
                        </p>
                      </a>
                    </li>-->
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url ?>?page=all_subjects" class="nav-link nav-all_subjects">
                        <i class="nav-icon fas fa-book-open"></i>
                        <p>
                          Select Subjects
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
        $('#find-transaction [name="tracking_code"]').focus();
      })
			uni_modal("Enter Tracking Number","transaction/find_transaction.php");
		})
    })
  </script>