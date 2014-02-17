<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Singapore');
class Manager extends CI_Controller {

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
		} else if ($this->session->userdata('acct_type') != "manager") {
			redirect(site_url("desktop/user/index"));
			return;
		}
	}
	
	public function index() {
		
		$query = $this->db->get_where('buildings', array('created_by' => $this->session->userdata('userid')), 1);
		if ($query->num_rows() > 0) {
			$data["has_office"] = "1";
			$office_id = $query->first_row()->id;
			
			$clickee_sql = "
			SELECT 1 FROM  switches, rooms, floors, buildings 
			WHERE switches.room_id = rooms.id AND rooms.floor_id = floors.id AND floors.building_id = buildings.id
			AND buildings.id = ?";
			$clickee_query = $this->db->query($clickee_sql, array($office_id));
			if ($clickee_query->num_rows() > 0) {
				$data["has_clickee"] = "1";
			} else {
				$data["has_clickee"] = "0";
			}
		}else {
			$data["has_office"] = "0";
			$data["has_clickee"] = "0";
		}
		
		
		
		$this->load->view('version2/ManagerDashboard.php', $data);
	}
	
	public function instruction() {
		echo "this is instruction page";
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
		$this->load->view('version2/ManageRule.php', $data);
	}
	
	public function getRequest($office_id) {
		$sql = "SELECT requests.id as id, users.id as user_id, users.username as username, department, position, compID, status,requests. created_at as created_at FROM requests, buildings, users WHERE requests.office_id = ".$office_id." AND requests.office_id = buildings.id AND status = '0' AND users.id = requests.user_id ORDER BY requests.created_at DESC";
		$query = $this->db->query($sql);
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($query->result()));
	}
	
	public function acceptRequest($request_id) {
		$check_query = $this->db->get_where('requests', array("id" => $request_id), 1);
		if ($check_query->num_rows() >0) {
			$user_id = $check_query->first_row()->user_id;
			$office_id = $check_query->first_row()->office_id;
			$this->db->where('id', $request_id);
			$this->db->update('requests', array('status' => '1')); 
			$this->db->insert('user_building', array("user_id" => $user_id, "building_id" => $office_id, "status" => 1)); 
			echo "1";
		} else {
			echo "no such request";
		}
	}
	
	public function rejectRequest($request_id) {
		$this->db->where('id', $request_id);
		$this->db->update('requests', array('status' => '2')); 
		echo "1";
	}
	
	public function configureOffice($office_id) {
		
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
				$room_sql =  "SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description, count(switches.id) AS no_switches, rooms.type as type FROM  rooms LEFT JOIN switches on rooms.id = switches.room_id WHERE rooms.floor_id = ".$floor["id"]." GROUP BY rooms.id";
				$room_query = $this->db->query($room_sql);
				if (!empty($room_query->first_row()->id)) {
					$floors[$floor["id"]]  = $room_query->result_array();
				} else {
					$floors[$floor["id"]]  = array();
				}
			};
		}
		
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;
		$data["floors"] = $floors;
		
		$this->load->view('version2/ConfigureOffice.php', $data);
	}
	
	public function managerControlRoom($office_id, $qr_floor_id, $qr_room_id) {
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
				$room_sql =  "SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description,  rooms.type AS type, count(switches.id) AS no_switches, rooms.notify AS notify FROM  rooms LEFT JOIN switches on rooms.id = switches.room_id WHERE rooms.floor_id = ".$floor["id"]." GROUP BY rooms.id";
				$room_query = $this->db->query($room_sql);
				if (!empty($room_query->first_row()->id)) {
					$floors[$floor["id"]]  = $room_query->result_array();
				} else {
					$floors[$floor["id"]]  = array();
				}
			};
			
			
				// get employrr
			$employee_sql = "SELECT * FROM users WHERE users.id IN (SELECT user_id FROM user_building WHERE building_id = ".$office_id." )";
			$employee_query = $this->db->query($employee_sql);
			foreach ($employee_query->result() as $employee_row) {
				$employees[$employee_row->id] = array("id" => $employee_row->id, "name" => $employee_row->username, "email" => $employee_row->email , "tel" => $employee_row->tel);
			}
		}
		
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;
		$data["floors"] = $floors;
		$data["employees"] = $employees;
		
		$data["selected_floor"] = $qr_floor_id;
		
		$data["selected_room"] = $qr_room_id;
		
		$this->load->view('version2/ManagerControlRoom.php', $data);
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
		$this->load->view('version2/ManageEmployeesList.php', $data);
	}
}
