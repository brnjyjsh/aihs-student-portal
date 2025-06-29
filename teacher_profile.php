<?php
$user = $conn->query("SELECT s.*, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname FROM teacher_list s WHERE s.id = '{$_settings->userdata('id')}'");

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
                <a href="./?page=teacher_manage_account" class="btn btn-default bg-navy btn-flat" style="border: 1px black solid;"><i class="fa fa-edit"></i> Update Account</a>
            </div>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-2 col-sm-12 py-2">
                            <center>
                                <img src="<?= $imageSrc ?>" alt="Teacher Image" class="img-fluid student-img bg-gradient-dark border">
                            </center>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <dl>
                                <dt class="text-yellow">Teacher Name:</dt>
                                <dd class="text-white pl-4"><?= ucwords($fullname) ?></dd>
                                <dt class="text-yellow">Employee ID:</dt>
                                <dd class="text-white pl-4"><?= ucwords($roll) ?></dd>
                                <dt class="text-yellow">Gender:</dt>
                                <dd class="text-white pl-4"><?= ucwords($gender) ?></dd>
                                <dt class="text-yellow">Birthdate:</dt>
                                <dd class="text-white pl-4"><?= date("F d, Y", strtotime($dob)) ?></dd>


                            </dl>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <dt class="text-yellow">Username</dt>
                            <dd class="text-white pl-4"><?= $username ?></dd>
                            <dt class="text-yellow">Initial Password</dt>
                            <dd class="text-white pl-4"><?= str_replace('-', '', $dob) ?></dd>
                            <dt class="text-yellow">Contact No.:</dt>
                            <dd class="text-white pl-4"><?= $contact ?></dd>
                            <dt class="text-yellow">Email:</dt>
                            <dd class="text-white pl-4"><?= $email_address ?></dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
