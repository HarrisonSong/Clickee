<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->library('session');			
		$this->load->helper('url');
		$this->load->database();		
			
		define('ASSEST_URL', base_url().'assets/');	
		
		if ($this->session->userdata('logged_in') == FALSE) {
			redirect("mobile/general/index");
			return;
		}
	}
	
	public function index() {
		$this->load->view('mobile/user_index.php', $data);
	}
	
	public function offices() {
		$this->load->view('mobile/offices.php', $data);
	}
	
	public function rooms($room_id) {
		
		$room_sql =  "SELECT rooms.id AS id, rooms.name AS name, buildings.id AS building_id FROM rooms, floors, buildings WHERE rooms.floor_id = floors.id AND floors.building_id = buildings.id AND rooms.id = ".$room_id;
		$room_query = $this->db->query($room_sql);
		$data["room"] = $room_query->first_row();
		
		$data["room_id"] = $room_id;
		$this->load->view('mobile/room_details.php', $data);
	}
	
	public function building_details($building_id) {
		
		if (empty($building_id)) {
			echo "There is no such building";
			return;
		}
		
		$buil_info_query = $this->db->get_where('buildings', array('id' => $building_id), 1);
		$data["office"] = $buil_info_query->first_row();
		
		$sql = "SELECT floors.id AS id, rooms.id AS room_id, name, description FROM floors LEFT JOIN rooms ON floors.id = rooms.floor_id WHERE building_id = '".$building_id."' ";
		$query = $this->db->query($sql);
		$data["floors"] = array();
		foreach ($query->result() as $row) {
			if ($row->room_id == NULL) {
				$data["floors"][$row->id] = array();
			} else {
				$count_sql = "SELECT count(appliances.current_status) AS no_appliance, IFNULL(sum(appliances.current_status), 0)  AS active_appliance FROM switches LEFT JOIN appliances ON switches.id = appliances.switch_id WHERE switches.room_id = '".$row->room_id."'  AND appliances.name != ''";
				$count_query = $this->db->query($count_sql);
				$count_row = $count_query->first_row();
				
				$data["floors"][$row->id][$row->room_id] = array("id" => $row->room_id, "name" => $row->name, "description" => $row->description, "no_appliance" => $count_row->no_appliance, "active_appliance" => $count_row->active_appliance) ;
			}
		}
		
		$this->load->view('mobile/building_details.php', $data);
	}
	
	
	public function bookings() {
		
	}
	
	// AJAX --------------
	
	public function getRoomInfo($room_id) {
		$sql = "SELECT *,  IF(TIMEDIFF(NOW(), update_at) < '00:00:30', 0, 1) AS is_error FROM switches LEFT JOIN appliances ON switches.id = appliances.switch_id WHERE switches.room_id = '".$room_id."'  ";
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
		   $data[$row->id]["no_ports"] = $row->no_appliances;
		   if ($row->port_id != NULL && !empty($row->name)) {
		   		$data[$row->id][$row->port_id] = array("name" => $row->name, "current_status" => $row->current_status, "is_error" => $row->is_error);
		   }
		}
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
	
	public function update_info() {
		$name = $this->input->post('updateName');
		$email = $this->input->post('updateEmail');
		$tel = $this->input->post('updatePhone');
		
		if (empty($name) || empty($email)) {
			echo "The required fields are empty.";
			return;
		}
		
		if ($email != $this->session->userdata('email')) {
			// check new email address is exist or not
			$query = $this->db->get_where('users', array('email' => $email), 1);
			if ($query->num_rows() > 0) {
				echo " there is an existing account associated with my email address.";
				return;
			}
			
			$this->db->where('id', $this->session->userdata('userid'));
			$this->db->update('users', array("tel" => $tel, "username" => $name, "email" => $email)); 
			
			$this->session->set_userdata(array("username" => $name, "email" => $email, "tel" => $tel)); 
		} else {
			$this->db->where('id', $this->session->userdata('userid'));
			$this->db->update('users', array("tel" => $tel, "username" => $name)); 
			$this->session->set_userdata(array("username" => $name, "tel" => $tel)); 
		}
		
		echo "success";
	}
	
	
	public function getStatus($switch_id, $port_id) {
		//get current
		$sql = "SELECT * FROM appliances WHERE switch_id ='".$switch_id."' AND port_id = '".$port_id."' AND TIMEDIFF(NOW(), update_at)  < '00:01:00' ";
		$query = $this->db->query($sql);
		if ($query->num_rows() == 0) {
			echo "error";
			return;
		}
		
		echo $query->first_row()->current_status;
	}
	
	
	public function loadAllOffices() {
		$userid = $this->session->userdata('userid');
		
		$data = array();
		
		$buil_query = $this->db->get_where('buildings', array('created_by' => $userid));
		foreach ($buil_query->result() as $buil_row) {
			$count_sql = "SELECT count(appliances.current_status) AS no_appliance, IFNULL(sum(appliances.current_status), 0)  AS active_appliance FROM floors, rooms, switches, appliances
					WHERE floors.building_id = '".$buil_row->id."' AND floors.id = rooms.floor_id AND switches.room_id = rooms.id AND appliances.switch_id = switches.id AND appliances.name != '' ";
			$count_query =$this->db->query($count_sql);
			$count_row = $count_query->first_row();
			$data[$buil_row->id] = array("name" => $buil_row->name, "description" => $buil_row->description, "total_appliances" => $count_row->no_appliance, "total_active_appliances" => $count_row->active_appliance);
		}
		 
		 $this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
		
	
	public function loadControl() {
		$data = array();
		$rooms_id = array();
		$room_id_query = $this->db->get_where('control_permission', array('user_id' => $this->session->userdata('userid'), "status" => 1));
		
		if ($room_id_query->num_rows() == 0) {
			$this -> output -> set_content_type('application/json') -> set_output(json_encode(""));
			return;
		}
		
		foreach ($room_id_query->result() as $room_id_row) {
			$room_details_sql = "SELECT buildings.id AS id, buildings.name AS building_name, buildings.description AS building_description, rooms.name AS room_name, rooms.description AS room_description FROM buildings, floors, rooms WHERE buildings.id = floors.building_id AND floors.id = rooms.floor_id AND rooms.id = '".$room_id_row->room_id."' LIMIT 1";
			$room_details_query =$this->db->query($room_details_sql);
			$room_details_row = $room_details_query->first_row();
			$office_id = $room_details_query->first_row()->id;
			
			$count_sql = "SELECT count(appliances.current_status) AS no_appliance, IFNULL(sum(appliances.current_status), 0)  AS active_appliance FROM rooms, switches, appliances
					WHERE switches.room_id = rooms.id AND appliances.switch_id = switches.id AND appliances.name != '' AND rooms.id = '".$room_id_row->room_id."' ";
			$count_query =$this->db->query($count_sql);
			$count_row = $count_query->first_row();
			$data [$office_id]["rooms"][$room_id_row->room_id] = array("name" =>  $room_details_row->room_name, "description" => $room_details_row->room_description, "total_appliances" => $count_row->no_appliance, "total_active_appliances" => $count_row->active_appliance);
			$data[$office_id]["info"] = array("name" => $room_details_row->building_name, "description" => $room_details_row->building_description);
		}
		 $this -> output -> set_content_type('application/json') -> set_output(json_encode($data));
	}
	
	
	public function logout() {
		$this->session->set_userdata('logged_in', FALSE);
		$this->session->destroy();
		redirect("mobile/general/index");
	}	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */