<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			$sys = $this->db->query("SELECT * FROM system_configuration limit 1")->fetch_array();

			foreach ($sys as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['sys'][$key] = $value;
			}

				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$qry = $this->db->query("SELECT * FROM users where username = '".$email."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
			}
			return 1;
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", address = '$address' ";
		$data .= ", contact = '$contact' ";
		$data .= ", electricity_rate = '$electricity_rate' ";
		$data .= ", water_rate = '$water_rate' ";
		
		$chk = $this->db->query("SELECT * FROM system_configuration");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_configuration set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_configuration set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_configuration limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['setting_'.$key] = $value;
		}

			return 1;
				}
	}

	
	function save_block(){
		extract($_POST);
		$data = " block = '$block' ";
		$data .= ", floor = '$floor' ";
		$data .= ", rate = '$rate' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO block_locations set ".$data);
		}else{
			$save = $this->db->query("UPDATE block_locations set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_block(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM block_locations where id = ".$id);
		if($delete)
			return 1;
	}
	
	function save_tenant(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", owner = '$owner' ";
		$data .= ", contact = '$contact' ";
		$data .= ", block_ids = '".implode(",",$block_ids)."' ";
		
		if(empty($id)){
			foreach ($block_ids as $key => $value) {
				$this->db->query("UPDATE block_locations set status = 2 where id=".$value);
			}
			$save = $this->db->query("INSERT INTO tenants set ".$data);
		}else{
			$bids = $this->db->query("SELECT * FROM tenants where id=".$id)->fetch_array()['block_ids'];
			$bids = explode(",",$bids);
			foreach ($bids as $key => $value) {
				$this->db->query("UPDATE block_locations set status = 1 where id=".$value);
			}
			foreach ($block_ids as $key => $value) {
				$this->db->query("UPDATE block_locations set status = 2 where id=".$value);
			}
			$save = $this->db->query("UPDATE tenants set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_tenant(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM tenants where id = ".$id);
		if($delete)
			return 1;
	}
	function save_billing(){
		extract($_POST);
		$data = " tenant_id = $tenant_id ";
		$billing_date = $billing_date.'-01';
		$data .= ", billing_date = '$billing_date' ";
		$data .= ", total_amount = '$o_amount' ";
		

		if(empty($id)){
			$save = $this->db->query("INSERT INTO billing set ".$data);
			
			if($save){
				$id = $this->db->insert_id;
				foreach($amount as $k => $v){
					$data=" billing_id = $id ";
					$data.=", type = '$k' ";
					$data.=", amount = '$v' ";
					$data.=", rate = '$rate[$k]' ";
					if(isset($consumption[$k]))
					$data.=", consumption = '$consumption[$k]' ";
					if(isset($reading[$k]))
					$data.=", reading = '$reading[$k]' ";
					if(isset($previous_amount[$k]))
					$data.=", previous_amount = '$previous_amount[$k]' ";
					if(isset($previous_consumption[$k]))
					$data.=", previous_consumption = '$previous_consumption[$k]' ";
					if(isset($previous_reading[$k]))
					$data.=", previous_reading = '$previous_reading[$k]' ";
					$ins[] = $this->db->query("INSERT INTO bills set ".$data);
				}
				return json_encode(array("status"=>1,"id"=>$id));
			}
		}else{
			$save = $this->db->query("UPDATE billing set ".$data." where id=".$id);
			if($save){
				foreach($amount as $k => $v){
					$data =" amount = '$v' ";
					$data.=", rate = '$rate[$k]' ";
					if(isset($consumption[$k]))
					$data.=", consumption = '$consumption[$k]' ";
					if(isset($reading[$k]))
					$data.=", reading = '$reading[$k]' ";
					if(isset($previous_amount[$k]))
					$data.=", previous_amount = '$previous_amount[$k]' ";
					if(isset($previous_consumption[$k]))
					$data.=", previous_consumption = '$previous_consumption[$k]' ";
					if(isset($previous_reading[$k]))
					$data.=", previous_reading = '$previous_reading[$k]' ";
					$ins[] = $this->db->query("UPDATE bills set ".$data." where type='$k' and billing_id = '$id' ");
				}
				return json_encode(array("status"=>1,"id"=>$id));
			}
		}
	}
	function delete_billing(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM billing where id = ".$id);
		$delete2 = $this->db->query("DELETE FROM bills where billing_id = ".$id);
		if($delete && $delete2){
				return 1;
			}
	}
	function get_pdetails(){
		extract($_POST);
		$get = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name, concat(address,', ',street,', ',baranggay,', ',city,', ',state,', ',zip_code) as caddress FROM persons where tracking_id = $tracking_id ");
		$data = array();
		if($get->num_rows > 0){
			foreach($get->fetch_array() as $k => $v){
				$data['status'] = 1;
				if(!is_numeric($k)){
					if($k == 'name')
						$v = ucwords($v);
					$data[$k]=$v;
				}
			}
		}else{
			$data['status'] = 2;
		}
		return json_encode($data);
		
	}
	function save_payment(){
		extract($_POST);
		$data = " amount_tendered = '$amount_tendered' ";
		$data .= ", amount_change = '$amount_change' ";
		$data .= ", status = 1 ";
		$save = $this->db->query("UPDATE billing set $data where id = $id ");
		if($save)
		return 1;
	}
}