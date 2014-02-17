<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Singapore');

class qrcode extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->library('session');			
		$this->load->helper('url');
		$this->load->database();
		define('ASSEST_URL', base_url().'assets/');	
	}
	
	public function room($room_id) {
		// Get room information
		$room_sql = "
			SELECT rooms.id AS id, rooms.name AS name, buildings.name AS buil_name, buildings.id AS building_id, buildings.created_by AS created_by, floors.id AS floor_id 
			FROM rooms, floors, buildings
			WHERE rooms.floor_id = floors.id 
						AND floors.building_id = buildings.id
						AND rooms.id = ?
		";
		$query = $this->db->query($room_sql, $room_id);
			
		// check the permission
		$room = $query->first_row();
		$data["room"] = $room;
		
		if($this->session->userdata('logged_in') == FALSE) {
			$this->session->set_userdata("qrroom", $room_id);
			$data["type"] = "login";
		} else {
			$user_id = $this->session->userdata('userid');
			
			if ($this->session->userdata('acct_type') == "manager") {
				if ($room->created_by == $user_id) {
					redirect(site_url("desktop/manager/managerControlRoom/".$room->building_id."/".$room->floor_id."/".$room->id));
					return;
				}else {
					$data["type"] = "manager";
				}
			} else {
				$check_sql = "
					SELECT 1
					FROM  user_building
					WHERE user_building.user_id = ?
								AND user_building.building_id = ?
								AND status = 1
				";
				$check_query = $this->db->query($check_sql, array($user_id, $room->building_id));
				if ($check_query->num_rows() > 0) {
					redirect(site_url("desktop/employee/booking/".$room->building_id."/".$room->floor_id."/".$room->id));
					return;
				} else {
					$data["type"] = "employee";
				}
			}
		}
		
		$this->load->view('version2/roomQR.php', $data);
	}
	
}
