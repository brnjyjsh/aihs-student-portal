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

// Define the image source based on the gender
$imageSrc = ($gender === 'Male') ? 'uploads/male_placeholder.png' : 'uploads/female_placeholder.png';
?>
<style>
    .student-img {
        object-fit: scale-down;
        object-position: center center;
        height: 200px;
        width: 200px;
    }
    .bgimg {
    background-image: url('uploads/homebg.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50% 50%;
    }
</style>
<div class="content col-lg-12 py-2">
    <div class="card card-outline card-navy shadow rounded-0 bgimg">
        <div class="card-header rounded-0">
            <h4 class="text-white card-title bg-navy" style="border: 1px black solid; width: 200px; height: 35px; background: #195905; color:#d1a827; text-align:center; padding-top: 5px;">Account Information</h4>
            <div class="card-tools">
                <a href="./?page=manage_account" class="btn btn-default bg-navy btn-flat" style="border: 1px black solid;"><i class="fa fa-edit"></i> Update Account</a>
            </div>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 py-2">
                            <center>
                                <img src="<?= $imageSrc ?>" alt="Student Image" class="img-fluid student-img bg-gradient-dark border">
                            </center>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <dl>
                                <dt class="text-yellow">Name:</dt>
                                <dd class="text-white pl-4"><?= ucwords($fullname) ?></dd>
                                
                                <dt class="text-yellow">Gender:</dt>
                                <dd class="text-white pl-4"><?= ucwords($gender) ?></dd>
                                <dt class="text-yellow">Birthdate:</dt>
                                <dd class="text-white pl-4"><?= date("F d, Y", strtotime($dob)) ?></dd>
                                <dt class="text-yellow">Contact No.:</dt>
                                <dd class="text-white pl-4"><?= $contact ?></dd>
                            </dl>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                                <dt class="text-yellow">Learner Reference Number (LRN):</dt>
                                <dd class="text-white pl-4"><?= $roll ?></dd>
                                <dt class="text-yellow">Track & Strand:</dt>
                                <dd class="text-white pl-4"><?= ucwords($strand) ?></dd>
                                <dt class="text-yellow">Grade Section:</dt>
                                <dd class="text-white pl-4">
                                <?php
                                if (isset($section_id)) {
                                    // Retrieve the section and grade level names from the section_list and grade_level_list tables
                                    $section_info = $conn->query("SELECT s.name AS section_name, g.name AS grade_level_name 
                                                                FROM `section_list` s 
                                                                JOIN `grade_level_list` g ON s.grade_level_id = g.id 
                                                                WHERE s.id = $section_id");

                                    $section_data = $section_info->fetch_assoc();
                                    if ($section_data) {
                                        echo $section_data['grade_level_name'] . ' - ' . $section_data['section_name'];
                                    } else {
                                        echo 'N/A';
                                    }
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                                </dd>
                                <dt class="text-yellow">Email:</dt>
                                <dd class="text-white pl-4"><?= $email_address ?></dd>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <?php if (!empty($guardian_name)): ?>
                                <dt class="text-yellow">Guardian:</dt>
                                <dd class="text-white pl-4"><?= $guardian_name ?></dd>
                            <?php endif; ?>

                            <?php if (!empty($guardian_contact)): ?>
                                <dt class="text-yellow">Guardian Contact No.:</dt>
                                <dd class="text-white pl-4"><?= $guardian_contact ?></dd>
                            <?php endif; ?>
                        </div>
                        <div class="" style="text-align: center;">
                            <!--<a style="color: red; text-align: center;">If the information displayed is incorrect, <b>proceed to the ICT Coordinator</b> and ask them to correct the entries. Thank you!</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
