<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Singapore');
class Employee extends CI_Controller {

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
		}	else if ($this->session->userdata('acct_type') != "employee") {
			redirect(site_url("desktop/user/index"));
			return;
		}	
	}
	
	public function index($request_office_id) {
		if (empty($request_office_id)) {
			$data["request_office"] = "";
		} else {
			$request_office_query = $this->db->get_where('buildings', array('id' => $request_office_id), 1);
			$data["request_office"] = $request_office_query->first_row();
		}
		
		$request_query = $this->db->get_where('requests', array('user_id' => $this->session->userdata('userid')), 1);
		if ($request_query->num_rows() > 0) {
			$data["has_request"] = "1";
		} else {
			$data["has_request"] = "0";
		}
		
		$building_sql = "
			SELECT buildings.id AS id, buildings.name AS name
			FROM buildings, user_building
			WHERE user_building.building_id = buildings.id
						AND user_building.user_id = ?
		";
		$query = $this->db->query($building_sql, array('user_id' => $this->session->userdata('userid')));
		if ($query->num_rows() > 0) {
			$data["has_office"] = "1";
			$data["offices"] = $query->result_array();
		} else {
			$data["has_office"] = "0";
		}
		
		$this->load->view('version2/EmployeeDashboard.php', $data);
	}
	
	
	public function request() {
		$office_id = $this->input->post('office_id');
		$department = $this->input->post('department');
		$position = $this->input->post('position');
		$compID = $this->input->post('compID');
		$user_id = $this->session->userdata('userid');
		
		$query = $this->db->get_where('requests', array('user_id' => $user_id, "office_id" => $office_id, 'status' => '0'), 1);
		if ($query->num_rows() > 0) {
			echo "You have already sent the request to join this office";
		} else {
			$this->db->insert('requests', array("user_id" => $user_id, "office_id" => $office_id, "department" => $department, "position" => $position, "compID" => $compID, "created_at" =>  date('Y-m-d H:i:s')));
			echo "1";
		}
	}
	
	public function getRequest() {
		$user_id = $this->session->userdata('userid');
		$sql = "SELECT requests.id as id, requests.office_id as office_id,buildings.name as building_name, department, position, compID, status,requests. created_at as created_at FROM requests, buildings WHERE requests.user_id = ".$user_id." AND requests.office_id = buildings.id ORDER BY requests.created_at DESC";
		$query = $this->db->query($sql);
		$this -> output -> set_content_type('application/json') -> set_output(json_encode($query->result()));
	}
	
	
	
	public function booking($office_id, $qr_floor_id, $qr_room_id) {
		$offices = array();
		$sql = "SELECT * FROM buildings, user_building WHERE user_building.building_id =  buildings.id AND user_building.user_id = ".$this->session->userdata('userid');
		$office_query = $this->db->query($sql);
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
				$room_sql =  "SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description FROM  rooms WHERE rooms.floor_id = ".$floor["id"]." AND rooms.type = 1 GROUP BY rooms.id";
				$room_query = $this->db->query($room_sql);
				if (!empty($room_query->first_row()->id)) {
					$floors[$floor["id"]]  = $room_query->result_array();
				} else {
					$floors[$floor["id"]]  = array();
				}
			};
			
		}
		
		if (empty($qr_room_id)) {
			$data["qr_data"] = "";
		} else {
			$data["qr_data"] = array("floor_id" => $qr_floor_id, "room_id" => $qr_room_id);
		}
		
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;	
		$data["floors"] = $floors;
		$this->load->view('version2/RoomBooking.php', $data);
	}
	
	
	public function employeeControlRoom($office_id, $floor_id, $room_id) {
		$user_id = $this->session->userdata('userid');
		$current =  getdate();
		$current_time = $current["hours"] + $current["minutes"]/60;
		$offices = array();
		$offices_sql = "SELECT * FROM buildings WHERE EXISTS ( SELECT 1 FROM user_building WHERE user_building.user_id = ".$user_id." AND user_building.building_id = buildings.id)";
		$office_query = $this->db->query($offices_sql);
		if ($office_query->num_rows() > 0) {	
			foreach($office_query->result_array() as $office) {
				$offices[$office["id"]] = $office;
			};
			if (empty($office_id)) {
				$office_id = $office_query->first_row()->id;
			}
			
			// load details for office
			//$floor_query  = $this->db->get_where('floors', array('building_id' => $office_id));
			//$floors = array();
			
			/*
			foreach($floor_query->result_array() as $floor) {
				$room_sql =  "SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description, rooms.type AS type, count(switches.id) AS no_switches FROM  rooms LEFT JOIN switches on rooms.id = switches.room_id WHERE rooms.floor_id = ".$floor["id"]." 
				AND (EXISTS ( SELECT 1 FROM control_permission WHERE control_permission.user_id = ".$user_id." AND control_permission.room_id =  rooms.id AND status = '1') OR EXISTS ( SELECT 1 FROM booking WHERE booking.user_id = ".$user_id." AND booking.room_id = rooms.id AND booking.date = '".$current['year'].'-'.$current['mon'].'-'.$current['mday']."' AND booking.start_at <= ".$current_time." AND booking.end_at >= ".$current_time."))
				GROUP BY rooms.id";
				$room_query = $this->db->query($room_sql);
				if (!empty($room_query->first_row()->id)) {
					$floors[$floor["id"]]  = $room_query->result_array();
				} else {
					$floors[$floor["id"]]  = array();
				}
			};
			*/
			
			$private_rooms = array();
			$private_room_sql = "
				SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description, rooms.type AS type FROM  rooms, floors, buildings WHERE rooms.floor_id = floors.id AND floors.building_id = buildings.id AND buildings.id = ? AND EXISTS (SELECT 1 FROM control_permission WHERE control_permission.user_id = ? AND control_permission.room_id = rooms.id)
			";
			$private_room_query = $this->db->query($private_room_sql, array($office_id, $user_id));
			$private_rooms = $private_room_query->result_array();
			
			$booked_rooms = array();
			$booked_room_sql = "
				SELECT rooms.id AS id, rooms.name AS name, rooms.description AS description, rooms.type AS type, (? - booking.start_at ) AS is_active, booking.date AS booking_date, booking.start_at AS booking_start_at, booking.end_at AS booking_end_at  FROM  rooms, floors, buildings, booking WHERE rooms.floor_id = floors.id AND floors.building_id = buildings.id AND buildings.id = ? AND rooms.id = booking.room_id AND booking.user_id = ? and (booking.date > ? OR (booking.date =  ? AND booking.end_at > ?)) ORDER BY booking.date DESC, booking.start_at  DESC
			";
			$booked_room_query = $this->db->query($booked_room_sql, array($current_time, $office_id, $user_id,$current['year'].'-'.$current['mon'].'-'.$current['mday'], $current['year'].'-'.$current['mon'].'-'.$current['mday'],  $current_time));
			$booked_rooms_temp = $booked_room_query->result_array();
			foreach ($booked_rooms_temp as $booked_room) {
				$booked_rooms[$booked_room->id] = $booked_room;
				$count++;
			}
		}
		
		$data["offices"] = $offices;
		$data["currentOffice"] = $office_id;
		//$data["floors"] = $floors;
		
		$data["private_rooms"] = $private_rooms;
		$data["booked_rooms"] = $booked_rooms;
		
		//print_r($data["private_rooms"]);
		//print_r($data["booked_rooms"]);
		
		
		if (empty($floor_id)) {
			$floor_id = 0;
		}
		if (empty($room_id)) {
			$room_id = 0;
		}
		$data["selected_floor"] = $floor_id;
		$data["selected_room"] = $room_id;
		$this->load->view('version2/EmployeeControlRoom.php', $data);
	}
	
}
