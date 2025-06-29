<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_strand(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `strand_list` set {$data} ";
		}else{
			$sql = "UPDATE `strand_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `strand_list` where `name` = '{$name}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Strand Name already exists.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Strand has successfully added.";
				else
					$resp['msg'] = "Strand details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_strand(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `strand_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Strand has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_grade_level(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `grade_level_list` set {$data} ";
		}else{
			$sql = "UPDATE `grade_level_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `grade_level_list` where `name` = '{$name}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Grade Level Name already exists.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Grade Level has successfully added.";
				else
					$resp['msg'] = "Grade Level details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_grade_level(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `grade_level_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Grade Level has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_quarter(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `quarter_list` set {$data} ";
		}else{
			$sql = "UPDATE `quarter_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `quarter_list` where `name` = '{$name}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Quarter Name already exists.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Quarter has successfully added.";
				else
					$resp['msg'] = "Quarter details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_quarter(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `quarter_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Quarter has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_school_year(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `school_year_list` set {$data} ";
		}else{
			$sql = "UPDATE `school_year_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `school_year_list` where `name` = '{$name}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'School Year Name already exists.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "School Year has successfully added.";
				else
					$resp['msg'] = "School Year details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_school_year(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `school_year_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"School Year has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_semester(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `semester_list` set {$data} ";
		}else{
			$sql = "UPDATE `semester_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `semester_list` where `name` = '{$name}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = 'Semester Name already exists.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Semester has successfully added.";
				else
					$resp['msg'] = "Semester details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_semester(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `semester_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"School Year has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_teacher(){
		extract($_POST);
		$data = "";
	
		// Function to capitalize the first letter of each word
		function capitalizeFirstLetter($str){
			return ucwords(strtolower($str));
		}
	
		// Iterate through the POST data
		foreach($_POST as $k => $v){
			if (!in_array($k, ['id', 'password'])) {
				// Capitalize the first letter of the value if it's one of the specified fields
				$v = in_array($k, ['firstname', 'middlename', 'lastname']) ? capitalizeFirstLetter($v) : $v;
	
				if (!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
	
				if (!empty($data)) {
					$data .= ",";
				}
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		// Check if 'roll' is exactly 7 digits
		if (!is_numeric($roll) || strlen($roll) != 7) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Employee ID must be exactly 7 digits long.";
			return json_encode($resp);
		}
	
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}
	
		if (empty($id)){
			$sql = "INSERT INTO `teacher_list` SET {$data} ";
		} else {
			$sql = "UPDATE `teacher_list` SET {$data} WHERE id = '{$id}' ";
		}
	
		$check = $this->conn->query("SELECT * FROM `teacher_list` WHERE roll = '{$roll}' " . (!empty($id) ? " AND id != '{$id}' " : "") . " ")->num_rows;
	
		if ($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
		} else {
			$save = $this->conn->query($sql);
			if ($save) {
				$sid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['sid'] = $sid;
				$resp['status'] = 'success';
				if (empty($id)) {
					$resp['msg'] = "Teacher information successfully saved.";
				} else {
					$resp['msg'] = "Teacher information successfully updated.";
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occurred.";
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
		}
	
		if ($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
	
		return json_encode($resp);
	}
	
	
	
	function delete_teacher(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `teacher_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Teacher has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_trsubject(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `trsubject` set {$data} ";
		}else{
			$sql = "UPDATE `trsubject` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = " Teacher Subject has successfully added.";
			else
				$resp['msg'] = " Teacher Subject details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_trsubject(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `trsubject` where id = '{$id}'");
		if($get->num_rows > 0){
			$res = $get->fetch_array();
		}
		$del = $this->conn->query("DELETE FROM `trsubject` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Teacher Subject has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_teacher_status(){
		extract($_POST);
		
		$update = $this->conn->query("UPDATE `teacher_list` set status = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Teacher's Status has been updated successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_subject(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `subject_list` set {$data} ";
		}else{
			$sql = "UPDATE `subject_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `subject_list` where `name` = '{$name}' and `strand_id` = '{$strand_id}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = ' Subject Name already exists on the selected strand.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Subject has successfully added.";
				else
					$resp['msg'] = " Subject details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_subject(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `subject_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Subject has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_section(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `section_list` set {$data} ";
		}else{
			$sql = "UPDATE `section_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `section_list` where `name` = '{$name}' and `strand_id` = '{$strand_id}' ".(is_numeric($id) && $id > 0 ? " and id != '{$id}'" : "")." ")->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = ' Section Name already exists on the selected strand.';
			
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['id'] = $rid;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = " Section has successfully added.";
				else
					$resp['msg'] = " Section details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_section(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `section_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Section has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_student(){
		extract($_POST);
		$data = "";
	
		// Function to capitalize the first letter of each word
		function capitalizeFirstLetter($str){
			return ucwords(strtolower($str));
		}
	
		// Iterate through the POST data
		foreach($_POST as $k => $v){
			if (!in_array($k, ['id', 'password'])) {
				// Capitalize the first letter of the value if it's one of the specified fields
				$v = in_array($k, ['firstname', 'middlename', 'lastname']) ? capitalizeFirstLetter($v) : $v;
	
				if (!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
	
				if (!empty($data)) {
					$data .= ",";
				}
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		// Check if 'roll' is exactly 12 digits
		if (!is_numeric($roll) || strlen($roll) != 12) {
			$resp['status'] = 'failed';
			$resp['msg'] = "Learner Reference Number must be exactly 12 digits long.";
			return json_encode($resp);
		}
	
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}
	
		if (empty($id)){
			$sql = "INSERT INTO `student_list` SET {$data} ";
		} else {
			$sql = "UPDATE `student_list` SET {$data} WHERE id = '{$id}' ";
		}
	
		$check = $this->conn->query("SELECT * FROM `student_list` WHERE roll = '{$roll}' " . (!empty($id) ? " AND id != '{$id}' " : "") . " ")->num_rows;
	
		if ($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
		} else {
			$save = $this->conn->query($sql);
			if ($save) {
				$sid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['sid'] = $sid;
				$resp['status'] = 'success';
				if (empty($id)) {
					$resp['msg'] = "Student information successfully saved.";
				} else {
					$resp['msg'] = "Student information successfully updated.";
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occurred.";
				$resp['err'] = $this->conn->error . "[{$sql}]";
			}
		}
	
		if ($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
	
		return json_encode($resp);
	}
	
	
	function delete_student(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `student_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Student has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	
	function save_academic1(){
		extract($_POST);
		$dataStudentSubject = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($dataStudentSubject)) $dataStudentSubject .=",";
				$dataStudentSubject .= " `{$k}`='{$v}' ";
			}
		}
	
		if(empty($id)){
			$sqlStudentSubject = "INSERT INTO `student_subject` SET {$dataStudentSubject} ";
			$saveStudentSubject = $this->conn->query($sqlStudentSubject);
	
			$lastInsertId = $this->conn->insert_id;
	
			$sqlStudentGrade = "INSERT INTO `student_grade` SET `student_subject_id`='{$lastInsertId}', `grade`=0, `status`=1";
			$saveStudentGrade = $this->conn->query($sqlStudentGrade);
		} else {
			$sqlStudentSubject = "UPDATE `student_subject` SET {$dataStudentSubject} WHERE id = '{$id}' ";
			$saveStudentSubject = $this->conn->query($sqlStudentSubject);
	
			$sqlStudentGrade = "UPDATE `student_grade` SET `status`=1 WHERE `student_subject_id`='{$id}'";
			$saveStudentGrade = $this->conn->query($sqlStudentGrade);
		}
	
		if($saveStudentSubject && $saveStudentGrade){
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Student Subject has successfully added.";
			else
				$resp['msg'] = "Student Subject details have been updated successfully.";
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
			$resp['err'] = $this->conn->error."[{$sqlStudentSubject} | {$sqlStudentGrade}]";
		}
	
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success', $resp['msg']);
	
		return json_encode($resp);
	}

	function save_academic(){
		extract($_POST);
	
		$resp = array(); // Initialize the response array
	
		if (isset($student_ids) && is_array($student_ids)) {
			foreach ($student_ids as $student_id) {
				$dataStudentSubject = "";
				foreach ($_POST as $k => $v) {
					if (!in_array($k, array('id', 'student_ids'))) {
						if (!is_numeric($v)) {
							$v = $this->conn->real_escape_string($v);
						}
						if (!empty($dataStudentSubject)) {
							$dataStudentSubject .= ",";
						}
						$dataStudentSubject .= " `{$k}`='{$v}' ";
					}
				}
	
				if (empty($id)) {
					$sqlStudentSubject = "INSERT INTO `student_subject` SET {$dataStudentSubject}, `student_id`='{$student_id}' ";
					$saveStudentSubject = $this->conn->query($sqlStudentSubject);
	
					$lastInsertId = $this->conn->insert_id;
	
					$sqlStudentGrade = "INSERT INTO `student_grade` SET `student_subject_id`='{$lastInsertId}', `grade`='', `status`=1";
					$saveStudentGrade = $this->conn->query($sqlStudentGrade);
				} else {
					$sqlStudentSubject = "UPDATE `student_subject` SET {$dataStudentSubject} WHERE id = '{$id}' AND `student_id`='{$student_id}' ";
					$saveStudentSubject = $this->conn->query($sqlStudentSubject);
	
					$sqlStudentGrade = "UPDATE `student_grade` SET `status`=1 WHERE `student_subject_id`='{$id}'";
					$saveStudentGrade = $this->conn->query($sqlStudentGrade);
				}
	
				if ($saveStudentSubject && $saveStudentGrade) {
					$resp['status'] = 'success';
					if (empty($id)) {
						$resp['msg'] = "Student Subject has successfully added.";
					} else {
						$resp['msg'] = "Student Subject details have been updated successfully.";
					}
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = "An error occurred.";
					$resp['err'] = $this->conn->error . "[{$sqlStudentSubject} | {$sqlStudentGrade}]";
				}
	
				if ($resp['status'] == 'success') {
					$this->settings->set_flashdata('success', $resp['msg']);
				}
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "No students selected.";
		}
	
		return json_encode($resp);
	}
	
	function delete_academic(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `student_subject` where id = '{$id}'");
		if($get->num_rows > 0){
			$res = $get->fetch_array();
		}
		$del = $this->conn->query("DELETE FROM `student_subject` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Student Subject has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_student_status(){
		extract($_POST);
		
		$update = $this->conn->query("UPDATE `student_list` SET status = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Student's Status has been updated successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_student_promotion(){
		extract($_POST);
		
		$update = $this->conn->query("UPDATE `student_list` SET school_year_id = '{$school_year_id}', section_id = '{$section_id}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Student's Section & School Year have been updated successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function promote_student() {
		extract($_POST);
	
		// Check if all grades are filled for the students in the current section and school year
		$check_grades_query = $this->conn->query("SELECT sl.id
												 FROM `student_list` sl
												 LEFT JOIN `student_subject` ss ON sl.id = ss.student_id
												 LEFT JOIN `student_grade` sg ON ss.id = sg.student_subject_id
												 WHERE sl.section_id = '{$id}' AND sl.school_year_id = '{$sy}'
												 GROUP BY sl.id
												 HAVING SUM(CASE WHEN sg.grade IS NULL OR sg.grade = 0 THEN 1 ELSE 0 END) = 0");
	
		$students_with_all_grades_filled = [];
		$students_with_missing_grades = [];
		
		while ($row = $check_grades_query->fetch_assoc()) {
			if (empty($row['id'])) {
				continue; // Skip empty results
			}
			
			$student_id = $row['id'];
			$check_missing_grades_query = $this->conn->query("SELECT COUNT(*) as missing_count
															 FROM `student_subject` ss
															 LEFT JOIN `student_grade` sg ON ss.id = sg.student_subject_id
															 WHERE ss.student_id = '{$student_id}' AND (sg.grade IS NULL OR sg.grade = 0)");
	
			$missing_grades_data = $check_missing_grades_query->fetch_assoc();
			if ($missing_grades_data['missing_count'] > 0) {
				$students_with_missing_grades[] = $student_id;
			} else {
				$students_with_all_grades_filled[] = $student_id;
			}
		}
	
		// Update only the students with all grades filled
		$update = $this->conn->query("UPDATE `student_list`
									  SET school_year_id = '{$school_year_id}', section_id = '{$section_id}'
									  WHERE id IN (" . implode(',', $students_with_all_grades_filled) . ")");
	
		if ($update) {
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success', "Students' Promotion has been successful.");
	
			if (!empty($students_with_missing_grades)) {
				$resp['msg'] = "Some students were not promoted because some of their enrolled subjects are not yet graded.";
				$resp['not_promoted_students'] = $students_with_missing_grades;
			}
		} else {
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
	
		return json_encode($resp);
	}
	
	
	function save_grade(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `student_grade` set {$data} ";
		}else{
			$sql = "UPDATE `student_grade` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = " Student Grade has successfully added.";
			else
				$resp['msg'] = " Student Grade details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_grade(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `student_grade` where id = '{$id}'");
		if($get->num_rows > 0){
			$res = $get->fetch_array();
		}
		$del = $this->conn->query("DELETE FROM `student_grade` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success'," Student Grade has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function save_csv_data($fileData, $conn) {
		// Error handling and logging
		$error = '';
	
		// Check if CSV File has been sent successfully otherwise return error
		if (isset($fileData['tmp_name']) && !empty($fileData['tmp_name'])) {
			// Read CSV File
			$csv_file = fopen($fileData['tmp_name'], "r");
			if (!$csv_file) {
				$error = "Failed to open CSV file.";
			} else {
				// Row Iteration
				$rowCount = 0;
				$data = []; // Data to insert for batch insertion
	
				// Read CSV Data by row
				while (($row = fgetcsv($csv_file, 1000, ",")) !== FALSE) {
					if ($rowCount > 0) {
						// Sanitizing Data
						$firstname = addslashes($conn->real_escape_string($row[0]));
						$contact = addslashes($conn->real_escape_string($row[1]));
						$email_address = addslashes($conn->real_escape_string($row[2]));
	
						// Add Row data to insert value
						$data[] = "('$firstname', '$contact', '$email_address')";
					}
					$rowCount++;
				}
	
				// Close the CSV File
				fclose($csv_file);
	
				// Check if there's data to insert otherwise return error
				if (count($data) > 0) {
					// Convert Data values from array to string w/ comma separator
					$insert_values = implode(", ", $data);
	
					// MySQL INSERT Statement
					$insert_sql = "INSERT INTO `student_list` (`firstname`, `contact`, `email_address`) VALUES $insert_values";
	
					// Execute Insertion
					$insert = $conn->query($insert_sql);
					if ($insert) {
						// Data Insertion is successful
						$_SESSION['status'] = 'success';
						$_SESSION['message'] = 'Data has been imported successfully.';
					} else {
						// Data Insertion has failed
						$error = 'Import Failed! Error: ' . $conn->error;
					}
				} else {
					$error = 'CSV File Data is empty.';
				}
			}
		} else {
			$error = 'CSV File Data is missing.';
		}
	
		// Close database connection
		$conn->close();
	
		// Redirect to appropriate page or log error
		if ($error) {
			error_log($error); // Log error
			$_SESSION['status'] = 'error';
			$_SESSION['message'] = $error;
		}
		header('location: ./');
		exit;
	}
	
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_strand':
		echo $Master->save_strand();
	break;
	case 'delete_strand':
		echo $Master->delete_strand();
	break;
	case 'save_subject':
		echo $Master->save_subject();
	break;
	case 'delete_subject':
		echo $Master->delete_subject();
	break;
	case 'save_grade_level':
		echo $Master->save_grade_level();
	break;
	case 'delete_grade_level':
		echo $Master->delete_grade_level();
	break;
	case 'save_quarter':
		echo $Master->save_quarter();
	break;
	case 'delete_quarter':
		echo $Master->delete_quarter();
	break;
	case 'save_school_year':
		echo $Master->save_school_year();
	break;
	case 'delete_school_year':
		echo $Master->delete_school_year();
	break;
	case 'save_semester':
		echo $Master->save_semester();
	break;
	case 'delete_semester':
		echo $Master->delete_semester();
	break;
	case 'save_teacher':
		echo $Master->save_teacher();
	break;
	case 'delete_teacher':
		echo $Master->delete_teacher();
	break;
	case 'save_trsubject':
		echo $Master->save_trsubject();
	break;
	case 'delete_trsubject':
		echo $Master->delete_trsubject();
	break;
	case 'update_teacher_status':
		echo $Master->update_teacher_status();
	break;
	case 'save_section':
		echo $Master->save_section();
	break;
	case 'delete_section':
		echo $Master->delete_section();
	break;
	case 'save_student':
		echo $Master->save_student();
	break;
	case 'delete_student':
		echo $Master->delete_student();
	break;
	case 'save_academic1':
		echo $Master->save_academic1();
	break;
	case 'save_academic':
		echo $Master->save_academic();
	break;
	case 'delete_academic':
		echo $Master->delete_academic();
	break;
	case 'update_student_status':
		echo $Master->update_student_status();
	break;
	case 'update_student_promotion':
		echo $Master->update_student_promotion();
	break;
	case 'promote_student':
		echo $Master->promote_student();
	break;
	case 'save_grade':
		echo $Master->save_grade();
	break;
	case 'delete_grade':
		echo $Master->delete_grade();
	break;
	default:
		// echo $sysset->index();
		break;
}