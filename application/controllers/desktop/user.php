<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Singapore');
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->library('session');			
		$this->load->helper('url');
		$this->load->database();		
		$this->load->model('Rule_model');	
		$this->load->model('Booking_model');	
		define('ASSEST_URL', base_url().'assets/');		
		// check permission
		if($this->session->userdata('logged_in') == FALSE) {
			$this->session->set_userdata('redirect_link', current_url());
			redirect(site_url("desktop/general/index"));
			return;
		}				
	}
	
	
	public function typeSelection() {
		$type =  $this->input->post('type');
		if (empty($type) || ($type != "manager" && $type != "employee")) {
			$data["name"] = $this->session->userdata('username');
			$this->load->view('version2/typeSelection.php', $data);
		} else {
			$this->db->where('id', $this->session->userdata('userid'));
			$this->db->update('users', array("type" => $type));
			$this->session->set_userdata('acct_type', $type);
			redirect(site_url("desktop/user/index"));
		}
	}
	
	public function index() {
		$acct_type = $this->session->userdata('acct_type') ;
		$qrroom = $this->session->userdata('qrroom') ;
		
		if (empty($acct_type)) {
			redirect(site_url("desktop/user/typeSelection"));
			return;
		} else {
			
			if	(!empty($qrroom)) {
				$this->session->set_userdata("qrroom", 0);
				redirect(site_url("qrcode/room/".$qrroom));
				return;
			}
			
			 if ($acct_type=="manager"){
				redirect(site_url("desktop/manager/index"));
				return;
			} else {
				redirect(site_url("desktop/employee/index"));
				return;
			}
		}
	}
	
	
	// later remove
	
	
	
	
	public function joinOffice() {
		$this->load->view('desktop/JoinOffice.php', $data);
	}
	
	public function ajaxOffices() {
		$userid = $this->session->userdata('userid');
		$offices = array();
		$sql = "SELECT * FROM buildings WHERE created_by!= ".$userid." AND not exists (SELECT 1 FROM user_building WHERE user_building.building_id = buildings.id AND user_building.user_id = ".$userid.")";
		$office_query = $this->db->query($sql);
		if ($office_query->num_rows() > 0) {
			foreach($office_query->result_array() as $office) {
				$offices[$office["id"]] = $office;
			};
		}
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($offices));
	}
	
	public function ajaxJoinOffice($office_id) {
		$this->db->insert('user_building', array("user_id" => $this->session->userdata('userid'), "building_id" => $office_id, "status" => 1)); 
		echo "success";
	}
	
	public function manageFloorPlan($office_id) {
		$offices = array();
		$office_query = $this->db->get_where('buildings', array('created_by' => $this->session->userdata('userid')));
		if ($office_query->num_rows() > 0) {
			
			foreach($office_query->result_array() as $office) {
				$offices[$office["id"]] = $office;
			};
			
			if (empty($office_id)) {
				$office_id = $office_query->first_row()->id;
			}
			
			// load details for office
			$floor_query  = $this->db->get_where('floors', array('building_id' => $office_id));
			$floors = array();
			foreach($floor_query->result_array() as $floor) {
				$room_sql =  "SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description, rooms.type AS type, rooms.notify AS notify, count(switches.id) AS no_switches FROM  rooms LEFT JOIN switches on rooms.id = switches.room_id WHERE rooms.floor_id = ".$floor["id"]." GROUP BY rooms.id";
				$room_query = $this->db->query($room_sql);
				if (!empty($room_query->first_row()->id)) {
					$floors[$floor["id"]]  = $room_query->result_array();
				} else {
					$floors[$floor["id"]]  = array();
				}
			};
			
			$employee_sql = "SELECT * FROM users WHERE users.id IN (SELECT user_id FROM user_building WHERE building_id = ".$office_id." )";
			$employee_query = $this->db->query($employee_sql);
			foreach ($employee_query->result() as $employee_row)
			{
				$employees[$employee_row->id] = array("id" => $employee_row->id, "name" => $employee_row->username, "email" => $employee_row->email , "tel" => $employee_row->tel);
			}
		}
		
		$rules = $this->Rule_model->getRuleByOfficeId($office_id);
		$data["rules"] = $rules;
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;
		$data["floors"] = $floors;
		$data["employees"] = $employees;

		$this->load->view('desktop/ManageFloorPlan.php', $data);
	}
	
	public function manageEmployee($office_id) {
		$offices = array();
		$office_query = $this->db->get_where('buildings', array('created_by' => $this->session->userdata('userid')));
		if ($office_query->num_rows() > 0) {
			
			foreach($office_query->result_array() as $office) {
				$offices[$office["id"]] = $office;
			};
			
			if (empty($office_id)) {
				$office_id = $office_query->first_row()->id;
			}
		}
		
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;	
		$this->load->view('desktop/ManageEmployeesList.php', $data);
	}
	
	public function manageRule($office_id) {
		$offices = array();
		$office_query = $this->db->get_where('buildings', array('created_by' => $this->session->userdata('userid')));
		if ($office_query->num_rows() > 0) {
			
			foreach($office_query->result_array() as $office) {
				$offices[$office["id"]] = $office;
			};
			
			if (empty($office_id)) {
				$office_id = $office_query->first_row()->id;
			}
		}
		
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;	
		
		$rules = $this->Rule_model->getRuleByOfficeId($office_id);
		//print_r($rules);
		$data["rules"] = $rules;
		$this->load->view('desktop/ManageRule.php', $data);
	}
	
	public function ajaxRuleIdsOffice($office_id) {
		$rule_query = $this->db->get_where('rule_info', array('office_id' => $office_id));
		$rules = $rule_query->result();
		
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($rules));
	}
	
	public function ajaxRuleDetails($rule_id) {
		$rule = $this->Rule_model->getRule($rule_id);
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($rule));
	}
	
	
	
	
	
	
	public function addRuleInfo() {
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$office_id = $this->input->post('office_id');
		
		// insert
		$this->db->insert('rule_info', array("name" => $name, "office_id" => $office_id, "description" => $description)); 
		echo $this->db->insert_id();
	}
	
	public function addRuleSlot() {
		$rule_id = $this->input->post('rule_id');
		$day = $this->input->post('day'); // 0 -> 6, 0: Sunday
		$start_at_hh = intval($this->input->post('start_at_hh')); //hh:mm
		$start_at_mm = intval($this->input->post('start_at_mm')); //hh:mm
		$end_at_hh = intval($this->input->post('end_at_hh')); //hh:mm
		$end_at_mm = intval($this->input->post('end_at_mm')); //hh:mm
		
		$start_time = $start_at_hh + $start_at_mm/60;
		$end_time = $end_at_hh  + $end_at_mm/60;
		
		//check rule overlap
		
		$this->db->insert('rule_details', array("rule_id" => $rule_id, "day" => $day, "start_at" => $start_time, "end_at" => $end_time ));
		echo $this->db->insert_id();
	}
	
	public function deleteRuleSlot($slot_id) {
		$this->db->delete('rule_details', array('id' => $slot_id)); 
		echo "success";
	}
	
	public function deleteRule($rule_id) {
		// delete all slot destails
		$this->db->delete('rule_details', array('rule_id' => $rule_id)); 
		// delete all rooms which map with rule
		$this->db->delete('rule_room', array('rule_id' => $rule_id)); 
		
		
		$this->db->delete('rule_info', array('id' => $rule_id));
		echo "success"; 
	}
	
	
	public function logout() {
		$this->session->set_userdata('logged_in', FALSE);
		$this->session->destroy();
		redirect("desktop/general/index");
	}	
	
	
	//AJAX
	public function createOffice() {
		$name = $this->input->post('name');
		$description = $this->input->post('description');
		$no_floors = intval($this->input->post('no_floors'));
		
		if (empty($description)) {
			$description = "";
		}
		
		$this->db->insert('buildings', array("name" => $name, "description" => $description, "created_by" => $this->session->userdata('userid'))); 
		$building_id = $this->db->insert_id();
		
		for($i = 0; $i < $no_floors; $i++) {
			$this->db->insert('floors', array("building_id" => $building_id)); 
		}
		
		echo $building_id;
	}
	
	public function addFloor($office_id) {
		$this->db->insert('floors', array("building_id" => $office_id));
		echo $this->db->insert_id();
	}
	

	
	public function getEmloyeesByOfficeId($office_id) {
		$employee_sql = "SELECT * FROM users WHERE users.id IN (SELECT user_id FROM user_building WHERE building_id = ".$office_id." )";
		$employee_query = $this->db->query($employee_sql);
		foreach ($employee_query->result() as $employee_row) {
			$employees[$employee_row->id] = array("id" => $employee_row->id, "name" => $employee_row->username, "email" => $employee_row->email , "tel" => $employee_row->tel);
		}
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($employees));
	}
	
	public function addEmployeesToOffice() {
		$office_id = $this->input->post('office_id');
		$emails_string = $this->input->post('emails');
		$emails = explode(",", $emails_string);
		$user_ids = array();
		$this->db->where_in('email', $emails);
		$user_query = $this->db->get('users');
		
		$exist_emails = array();
		foreach ($user_query->result() as $user_row) {
			$check_query = $this->db->get_where('user_building', array("user_id" => $user_row->id, "building_id" => $office_id), 1);
			if ($check_query->num_rows() == 0) {
				$this->db->insert('user_building', array("user_id" => $user_row->id, "building_id" => $office_id, "status" => 1)); 
				// update the request if have
				$this->db->update('requests', array("status" => 1), array('user_id' => $user_row->id, 'office_id' => $office_id));
			}
			$exist_emails[$user_row->email] = 1;
		}
		
		$non_exist = array();
		foreach($emails as $email) {
			if ($exist_emails[$email] != 1) {
				$non_exist[$email] =$email;
			}
		}
		
		if (count($non_exist) == 0) {
			echo "success";
		} else {
			echo implode(",", $non_exist);
		}
	}
	
	public function removeEmployeesFromOffice() {
		$office_id = $this->input->post('office_id');
		$email_address = trim($this->input->post('email'));
		
	
		
		$sql = "DELETE FROM user_building WHERE building_id = ".$office_id." AND user_id = (SELECT id FROM users WHERE users.email = '".$email_address."' )";
		$this->db->query($sql);
		
		
		echo "success";
	}
	
	public function getRoomInfo($room_id) {
		$sql = "SELECT *, IF(TIMEDIFF(NOW(), update_at) < '00:00:15', 0, 1) AS is_error FROM switches LEFT JOIN appliances ON switches.id = appliances.switch_id WHERE switches.room_id = '".$room_id."'  ";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
		   $data[$row->id]["no_ports"] = $row->no_appliances;
		   if ($row->port_id != NULL && !empty($row->name)) {
		   		$data[$row->id][$row->port_id] = array("name" => $row->name, "current_status" => $row->current_status, "is_error" => $row->is_error);
		   }
		}
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
	
	
	public function getRoomBookingInfo($room_id, $mm, $dd, $yyyy) {
		if (empty($mm))
			$bookings = $this->Booking_model->getBookingInfoByRoomId($room_id);
		else {
			$bookings = $this->Booking_model->getBookingInfoByRoomId($room_id, $yyyy."-".$mm."-".$dd);
		}
		$user_id = $this->session->userdata('userid');
		foreach($bookings as $key => $booking) {
			$bookings[$key]->start_at = $bookings[$key]->getStartTime();
			$bookings[$key]->end_at = $bookings[$key]->getEndTime();
			
			if ($bookings[$key]->user_id == $user_id) {
				$bookings[$key]->owning = 1;
			} else {
				$bookings[$key]->owning = 0;
			}
		}
		
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($bookings));
	}
	
	
	public function bookRoom() {
		$room_id = $this->input->post('room_id');
		$user_id = $this->session->userdata('userid');
		$date = $this->input->post('date'); // mm/dd/yyyy
		$dateElements = explode("/", $date);
		$date = 	$dateElements[2]."-".$dateElements[0]."-".$dateElements[1];
		
		$start_at_hh = $this->input->post('start_at_hh');
		$start_at_mm = $this->input->post('start_at_mm');
		$end_at_hh = $this->input->post('end_at_hh');
		$end_at_mm = $this->input->post('end_at_mm');
		
		$start_time = $start_at_hh + $start_at_mm/60;
		$end_time = $end_at_hh  + $end_at_mm/60;
		
		//check rule overlap
		$isOverLap = $this->Booking_model->isOverlap($room_id, $date, $start_time, $end_time);
		if ($isOverLap == 1) {
			echo "overlap";
			return;
		}
		
		$this->db->insert('booking', array("user_id" => $user_id, "room_id" => $room_id, "date" => $date, "start_at" => $start_time, "end_at" => $end_time ));
		//echo $this->db->insert_id();
		echo "success";
	}
	
	public function deleteBooking($booking_id) {
		$this->db->delete('booking', array('id' => $booking_id)); 
		echo "success";
	}
	
	public function controlPermission($room_id) {
		$data = array();
		$sql = "SELECT users.id AS id,users.username AS username, users.email AS email, tel FROM control_permission LEFT JOIN users ON control_permission.user_id = users.id WHERE room_id = ".$room_id." AND  status = 1";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row)
		{
			$data[$row->id] = array("name" => $row->username, "email" => $row->email, "tel" => $row->tel);
		}
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
	
	public function addPermission() {
		$room_id = $this->input->post('room_id');
		$emails_string = $this->input->post('emails');
		$emails = explode(",", $emails_string);
		
		$user_ids = array();
		$this->db->where_in('email', $emails);
		$user_query = $this->db->get('users');
		foreach ($user_query->result() as $user_row) {
			$check_query = $this->db->get_where('control_permission', array("user_id" => $user_row->id, "room_id" => $room_id), 1);
			if ($check_query->num_rows() == 0) 
				$this->db->insert('control_permission', array("user_id" => $user_row->id, "room_id" => $room_id, "status" => 1)); 
		}
		
		echo "success";
	}
	
	
	public function removePermission() {
		$room_id =  intval($this->input->post('room_id'));
		$email_address = trim($this->input->post('email'));
		
		$sql = "DELETE FROM control_permission WHERE room_id = ".$room_id." AND user_id = (SELECT id FROM users WHERE users.email = '".$email_address."' )";
		$this->db->query($sql);
		echo "success";
	}
	
	
	public function getRoomRule($room_id) {
		$rule = $this->Rule_model->getRuleByRoomId($room_id);
		
		if (empty($rule)) {
			$this -> output -> set_content_type('application/json') -> set_output(json_encode(""));
			return;
		}
	
	/*	
		foreach($rule->slots as $day => $slots) {
			for($i = 0; $i < count($slots); $i++) {
				$rule->slots[$day][$i]->start_at = $rule->slots[$day][$i]->getStartTime();
				$rule->slots[$day][$i]->end_at = $rule->slots[$day][$i]->getEndTime();
			}
		}
		*/
		
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($rule));
	}
	
	public function setRoomRule($room_id, $rule_id) {
		
		if (empty($rule_id)) {
			$this->db->delete('rule_room', array('room_id' => $room_id)); 
			echo "1";
			return;
		}
		
		$this->db->delete('rule_room', array('room_id' => $room_id)); 
		
		//insert new one
		$this->db->insert('rule_room', array("room_id" => $room_id, "rule_id" => $rule_id)); 
		
		$rule = $this->Rule_model->getRule($rule_id);
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($rule));
		
		
	}
	
	public function changeRoomType($room_id, $type) {
		
		//change to common, remove the permission
		if ($type == '1') {
			$this->db->delete('control_permission', array('room_id' => $room_id)); 
		}
		$this->db->where('id', $room_id);
		$this->db->update('rooms', array("type" => $type));
		echo "success"; 
	}
	
	public function changeRoomNotify($room_id, $need_notify) {
		$this->db->where('id', $room_id);
		$this->db->update('rooms', array("notify" => $need_notify));
		echo "success";
	}
	
	/*
	public function createFloor($building_id) {
		$this->db->insert('floors', array("building_id" => $building_id)); 
		echo $this->db->insert_id();
	}*/
	
	public function createRoom() {
		$name = $this->input->post('name');
		$floor_id =  $this->input->post('floor_id');
		
		$this->db->insert('rooms', array("floor_id" => $floor_id, "name" => $name)); 
		echo $this->db->insert_id();
	}
	
	public function editRoom() {
		$room_id =  $this->input->post('room_id');
		$name = $this->input->post('name');
		
		$this->db->where('id', $room_id);
		$this->db->update('rooms', array("name" => $name));
		
		echo "success"; 
	}
	
	public function getBookingRoomsByOffice($office_id) {
		$sql = "SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description FROM rooms, floors, buildings where rooms.floor_id = floors.id AND floors.building_id = buildings.id AND buildings.id = ".$office_id." AND rooms.type = 1";
		$query = $this->db->query($sql);
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($query->result()));
	}
	
	public function addSwitch() {
		$room_id = $this->input->post('room_id');
		$switch_id = $this->input->post('switch_id');
		$no_ports = $this->input->post('no_ports');
		
		$ports = array();
		if (empty($room_id) || empty($switch_id) || empty($no_ports)) {
			echo "The required fields are empty.";
			return;
		}
		
		$switch_id = str_replace(' ', '_', $switch_id);
		
		$s_query = $this->db->get_where('switches', array('id' => $switch_id), 1);
		if ($s_query->num_rows() > 0) {
			echo "The Clickee ID has been taken. Please double check your Clickee ID.";
			return;
		}
		
		// insert switch
		$this->db->insert('switches', array("id" => $switch_id, "room_id" => $room_id, "no_appliances" => $no_ports)); 
		for($i = 1; $i <= $no_ports; $i++) {
			$name = $this->input->post('port'.$i);
			$this->db->insert('appliances', array("switch_id" => $switch_id, "port_id" => $i, "name" => $name)); 
		}
		
		echo "success";
	}
	
	public function editSwitch() {
		$switch_id = $this->input->post('switch_id');
		$no_ports = 0;
		$s_query = $this->db->get_where('switches', array('id' => $switch_id), 1);
		if ($s_query->num_rows() == 0) {
			echo "no such switch";
			return;
		} else {
			$no_ports = $s_query->first_row()->no_appliances;
			for($i = 1; $i <= $no_ports; $i++) {
				$name = $this->input->post('port'.$i);
				$this->db->where(array('switch_id' => $switch_id, "port_id" => $i));
				$this->db->update('appliances', array("name" => $name)); 
			}
		}
		echo "success";
	}
	
	public function getSwitch($switch_id) {
		$data = array();
		$s_query = $this->db->get_where('switches', array('id' => $switch_id), 1);
		if ($s_query->num_rows() == 0) {
			echo "no such switch";
			return;
		} else {
			$data["no_ports"] = $s_query->first_row()->no_appliances;
			$ap_query = $this->db->get_where('appliances', array('switch_id' => $switch_id));
			foreach ($ap_query->result() as $row)
		   {
				$data[$row->port_id] = $row->name;
		   }
		}
		
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
	
	public function deleteSwitch($switch_id) {
		//get room info
		$s_query = $this->db->get_where('switches', array('id' => $switch_id));
		if ($s_query->num_rows() == 0) {
			echo "no such switch";
			return;
		}
		$room_id = $s_query->first_row()->room_id;
		
		// delete devices
		$this->db->where('switch_id', $switch_id);
		$this->db->delete('appliances'); 
		
		//delete switch
		$this->db->where('id', $switch_id);
		$this->db->delete('switches'); 
		
		// get the remain switch
		$room_sql = "SELECT count(id) AS no_switches FROM switches WHERE room_id = '".$room_id."'";
		$room_query = $this->db->query($room_sql);
		$no_switches = $room_query->first_row()->no_switches;
		
		// echo info
		$data["room_id"] = $room_id;
		$data["no_switches"] = $no_switches;
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
	
	
	
	public function deleteRoom($room_id) {
		// delete devices
		$delete_device_sql = "DELETE FROM appliances WHERE appliances.switch_id IN (SELECT id FROM switches WHERE switches.room_id = '".$room_id."' )";
		 $this->db->query($delete_device_sql);
		 
		 // delete swich
		$this->db->where('room_id', $room_id);
		$this->db->delete('switches'); 
		
		// delete room
		$this->db->where('id', $room_id);
		$this->db->delete('rooms'); 
		
		echo "success";
	}
	
	public function deleteFloor($floor_id) {
		// delete devices
		$delete_device_sql = "DELETE FROM appliances WHERE appliances.switch_id IN (SELECT id FROM switches WHERE switches.room_id IN (SELECT id FROM rooms WHERE floor_id = '".$floor_id."'))";
		 $this->db->query($delete_device_sql);
		 
		// delete switches
		$delete_switches_sql = "DELETE FROM switches WHERE switches.room_id IN (SELECT id FROM rooms WHERE floor_id = '".$floor_id."')";
		$this->db->query($delete_switches_sql);
		
		// delete rooms
		$this->db->where('floor_id', $floor_id);
		$this->db->delete('rooms'); 
		
		// delete floors
		$this->db->where('id', $floor_id);
		$this->db->delete('floors');
		
		 echo "success";
	}
	
	public function deleteOffice($office_id) {
		// delete devices
		$delete_device_sql = "DELETE FROM appliances WHERE appliances.switch_id IN (SELECT id FROM switches WHERE switches.room_id IN (SELECT id FROM rooms WHERE floor_id IN ( SELECT id FROM floors WHERE building_id = '".$office_id."')))";
		 $this->db->query($delete_device_sql);
		 
		 // delete switches
		 $delete_switches_sql = "DELETE FROM switches WHERE switches.room_id IN (SELECT id FROM rooms WHERE floor_id IN  ( SELECT id FROM floors WHERE building_id = '".$office_id."'))";
		$this->db->query($delete_switches_sql);
		
		// delete rooms
		$delete_room_sql = "DELETE FROM rooms WHERE floor_id IN  ( SELECT id FROM floors WHERE building_id = '".$office_id."')";
		$this->db->query($delete_room_sql);
		
		// delete floor
		$this->db->where('building_id', $office_id);
		$this->db->delete('floors');
		
		// delete office
		$this->db->where('id', $office_id);
		$this->db->delete('buildings');
		
		echo "success";
	}
	
	public function action($switch_id, $port_id, $status) {
		
		if ($status == "1") {
			//check the rule and booking
			$room_type_sql = "SELECT type, room_id FROM rooms, switches WHERE rooms.id = switches.room_id AND switches.id = '".$switch_id."' LIMIT 1";
			$room_type_query =  $this->db->query($room_type_sql);
			$room_id = $room_type_query->first_row()->room_id;
			$room_type = $room_type_query->first_row()->type;
	
			$current =  getdate();
			$date = $current['year'].'-'.$current['mon'].'-'.$current['mday'];
			$current_time = $current["hours"] + $current["minutes"]/60;
			
			// CASE 1: room type 1, if no booking at this time, not allowed
			if ($room_type == 1) {
				$check_booking_sql = "SELECT 1 FROM booking WHERE room_id = ".$room_id." AND date = '".$date."' AND start_at <= ".$current_time." AND end_at >= ".$current_time;
				$check_booking_query =  $this->db->query($check_booking_sql);
				if ($check_booking_query->num_rows() == 0) {
					echo "This is a common room and no one books this room at this time. Hence, all the devices in this room should be off. If you want to turn it on, please change this room to private room.";
					return;
				}
			} else {
				// check whether there is any rule
				$rule_sql = "SELECT 1 FROM rule_room, rule_details WHERE rule_room.rule_id = rule_details.rule_id AND rule_room.room_id = ".$room_id." AND start_at <= ".$current_time." AND end_at >= ".$current_time;
				$rule_query =  $this->db->query($rule_sql);
				if ($rule_query->num_rows() > 0) {
					echo "This is a private room and you have set the rule that all devices must be off at this time. Hence, you can not turn it on. If you want to do that, please remove the rule first.";
					return;
				}
			}
		}
		
		
		// get the latest action only, ignore all old ones.
		$this->db->where(array('switch_id' => $switch_id, "port_id" => $port_id));
		$this->db->update('actions', array("is_check" => 1)); 
		
		$this->db->insert('actions', array("user_id" => $this->session->userdata('userid'), "switch_id" => $switch_id, "port_id" => $port_id, "status" => $status)); 
		echo "success";
	}
	
	public function updateInfo() {
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$tel = $this->input->post("tel");
		
		$this->db->update('users', array("username" => $username, "password" => $password, "tel" => $tel), array('id' => $this->session->userdata('userid')));
		
		$this->session->set_userdata(array("username" => $username, "tel" => $tel)); 
		echo "1";
	}
	
	
	public function invitationEmails() {
		$this->load->library('email');	
		$emails = $this->input->post("emails");
		
		$office_query = $this->db->get_where('buildings', array('created_by' => $this->session->userdata('userid')));
		$office_name = $office_query->first_row()->name;
		
		$message  = "
			<p>Dear Sir/Madam</p>
			<p>It has been indicated that you are an employee of ".$office_name." <br/>
			Click on <a href='http://clickee.drekee.com'>http://clickee.drekee.com</a> to create an account and request to join office to gain access to the system.<br/>
			Please ignore this email if you are not an employee of ".$office_name.".</p>
			<p>Thank you for your time.</p>
			<p>Regards,<br/>".$office_name."<br/></p>
			<p><em>*This is an auto-generated email through <a href='http://clickee.drekee.com'>Clickee - the next generation electrical wall switch.</a></em></p>
		";
		
		// send email welcome
		$this->email->set_newline("\r\n");
		$this->email->from('Clickee@clickee.drekee.com', 'Clickee');
		$this->email->to($emails); 				
		$this->email->subject('Invitation to join '.$office_name);
		$this->email->message($message);
		$abc =  $this->email->send();	
		
		echo "success";
	}
	
	public function editOffice() {
		$office_id = $this->input->post("office_id");
		$name = $this->input->post("name");
		$description = $this->input->post("description");
		
		$this->db->update('buildings', array("name" => $name, "description" => $description), array('id' => $office_id));
		echo "success";
	}
	
	public function aboutProduct() {
		$this->load->view('version2/aboutProduct.php', $data);
	}
}
