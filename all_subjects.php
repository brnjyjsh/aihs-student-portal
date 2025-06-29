<?php
$userID = $_settings->userdata('id');
$userQuery = $conn->prepare("SELECT s.*, CONCAT(s.lastname, ', ', s.firstname, ' ', s.middlename) as fullname FROM teacher_list s WHERE s.id = ?");
$userQuery->bind_param("i", $userID);
$userQuery->execute();

if ($userQuery) {
    $userData = $userQuery->get_result()->fetch_assoc();

    if ($userData) {
        foreach ($userData as $k => $v) {
            $$k = $v;
        }
    } else {
        // Handle the case when no user data is found
    }
} else {
    // Handle the case when there's an issue with the database query
}
?>
<style>
    .img-thumb-path {
        width: 100px;
        height: 80px;
        object-fit: scale-down;
        object-position: center center;
    }
</style>

<?php
    require_once('./config.php'); // Include your database connection and configuration file

    // Query to get the current school year with status = 1
    $querySchoolYear = $conn->query("SELECT * FROM `school_year_list` WHERE status = 1");

     if ($querySchoolYear->num_rows > 0) {
        $schoolYearData = $querySchoolYear->fetch_assoc();
        $activeSchoolYearID = $schoolYearData['id']; // Get the active school year ID
         $activeSchoolYearName = $schoolYearData['name'];
    } else {
    exit; // Exit if there's no active school year
    }
?>
    <!-- Conditionally display "First Semester" or "Second Semester" based on the active quarter -->
<?php
    // Query to get the current quarter and corresponding semester with status = 1
    $query = $conn->query("SELECT q.name AS quarter_name, s.name AS semester_name 
        FROM `quarter_list` q 
        INNER JOIN `semester_list` s ON 
        (q.name IN ('First Quarter', 'Second Quarter') AND s.name = 'First Semester') OR 
        (q.name IN ('Third Quarter', 'Fourth Quarter') AND s.name = 'Second Semester') 
        WHERE q.status = 1 AND s.status = 1");

        if ($query->num_rows > 0) {
            $data = $query->fetch_assoc();
            $activeQuarterName = $data['quarter_name'];
            $activeSemesterName = $data['semester_name'];
        } else {
            exit; // Exit if there's no active quarter or semester
                            }
?>
<div class="content py-2">
<div class="card card-outline card-navy rounded-0 shadow">
	<div class="card-body">
        <h2>Select Assigned Subjects</h2>
        <div class="container-fluid">
        <div class="container-fluid ">
			<table class="table table-bordered table-hover table-striped table-responsive">
				<colgroup>
					<col width="10%">
					<col width="30%">
					<col width="10%">
                    <col width="10%">
					<col width="1%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-navy text-light">
						<th class="text-center">Subject Code</th>
						<th class="text-center">Subject</th>
						<th class="text-center">Strand & Track</th>
                        <th class="text-center">Grade & Section</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
                            <?php
                            $qry = $conn->query("SELECT a.*, c.name AS subject, c.subject_code, d.name AS strand, e.name AS section, f.name AS quarter, g.name AS school_year, e.strand_id AS section_strand_id FROM `trsubject` a 
                            INNER JOIN subject_list c ON a.subject_id = c.id 
                            INNER JOIN strand_list d ON c.strand_id = d.id 
                            INNER JOIN section_list e ON a.section_id = e.id
                            INNER JOIN quarter_list f ON a.quarter_id = f.id
                            INNER JOIN school_year_list g ON a.school_year_id = g.id
                            WHERE teacher_id = '{$id}' 
                            AND a.school_year_id = '{$activeSchoolYearID}' 
                            AND f.name = '{$activeQuarterName}' ");

                            if ($qry === false) {
                                // Handle query execution error
                                die("Query failed: " . $conn->error);
                            }

                            while ($row = $qry->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject_code'] ?></span></td>
                                    <td class="px-2 py-1 align-middle text-center"><span class=""><?= $row['subject'] ?></span></td>
                                    <td class="px-2 py-1 align-middle text-center">
                                        <?php 
                                        if (isset($row['section_strand_id'])) {
                                            $strand_id = $row['section_strand_id'];
                                            // Retrieve the strand name from the strand_list table
                                            $strand_info = $conn->query("SELECT name FROM `strand_list` WHERE id = $strand_id");

                                            $strand_data = $strand_info->fetch_assoc();
                                            if ($strand_data) {
                                                echo $strand_data['name'];
                                            } else {
                                                echo 'N/A';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td><td class="align-middle text-center"><p class="m-0 truncate-1">
                                    <?php 
                                    if (isset($row['section_id'])) {
                                        $section_id = $row['section_id'];
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
                                    </p></td>
                                    <td align="center">
                                        <?php
                                        if ($row['status'] == 1) {
                                            // If the status is active, allow the button to be clickable
                                            echo '<a href="./?page=selected_subject&id=' . $row['id'] . '" class="btn btn-flat btn-default btn-sm border">Select</a>';
                                        } else {
                                            // If the status is inactive, make the button unclickable
                                            echo '<button class="btn btn-flat btn-default btn-sm border" disabled>Select</button>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        /*$('#create_new').click(function(){
			uni_modal("Add New grade_level","grade_level/manage_grade_level.php")
		})
		$('.view_data').click(function(){
			uni_modal("grade_level Details","grade_level/view_grade_level.php?id="+$(this).attr('data-id'))
		})
        $('.edit_data').click(function(){
			uni_modal("Update grade_level Details","grade_level/manage_grade_level.php?id="+$(this).attr('data-id'))
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this grade_level permanently?","delete_grade_level",[$(this).attr('data-id')])
		})*/
        $('.table td, .table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
        });
    })

    function delete_grade_level($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_grade_level",
            method: "POST",
            data: { id: $id },
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
