<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		extract($_POST);

		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password')");
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] != 1){
				return json_encode(array('status'=>'notverified'));
			}
			foreach($res as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}
			}
			$this->settings->set_userdata('login_type',1);
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}
	public function logout(){
		if($this->settings->sess_des()){
			redirect('../sis/login.php');
		}
	}
	function user_login() {
		extract($_POST);
	
		if (empty($username) || empty($password)) {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Username and password are required.';
			return json_encode($resp);
		}
	
		// Define an array of tables to check
		$tables = ['student_list', 'teacher_list', 'users'];
	
		foreach ($tables as $table) {
			$query = $this->conn->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname FROM $table WHERE username = '$username' AND `password` = MD5('$password') ");
	
			if ($query === false) {
				$resp['status'] = 'failed';
				$resp['msg'] = "Error executing query for $table: " . $this->conn->error;
				return json_encode($resp);
			}
	
			if ($query->num_rows > 0) {
				$res = $query->fetch_array();
	
				if ($res['status'] == 1) {
					foreach ($res as $k => $v) {
						$this->settings->set_userdata($k, $v);
					}
					$this->settings->set_userdata('login_type', 2);
					
					$resp['status'] = 'success';
					return json_encode($resp);
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = "Your account is not verified yet.";
					return json_encode($resp);
				}
			}
		}
	
		// If no matching user found
		$resp['status'] = 'failed';
		$resp['msg'] = "Invalid username or password.";
		return json_encode($resp);
	}
	
	
	public function student_logout(){
		if($this->settings->sess_des()){
			redirect('./login.php');
		}
	}
	function teacher_login(){
		extract($_POST);
		$qry = $this->conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from teacher_list where username = '$username' and `password` = md5('$password') ");
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
		}else{
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] == 1){
				foreach($res as $k => $v){
					$this->settings->set_userdata($k,$v);
				}
				$this->settings->set_userdata('login_type',2);
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "Your Account is not verified yet.";
			}
			
		}else{
		$resp['status'] = 'failed';
		$resp['msg'] = "Invalid username or password.";
		}
		}
		return json_encode($resp);
	}
	public function teacher_logout(){
		if($this->settings->sess_des()){
			redirect('./login.php');
		}
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'user_login':
		echo $auth->user_login();
		break;
	case 'student_logout':
		echo $auth->student_logout();
		break;
	case 'teacher_login':
		echo $auth->teacher_login();
		break;
	case 'teacher_logout':
		echo $auth->teacher_logout();
		break;
	default:
		echo $auth->index();
		break;
}

