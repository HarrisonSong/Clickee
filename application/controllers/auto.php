<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('date.timezone', 'Asia/Singapore');

class Auto extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->library('session');			
		$this->load->helper('url');
		$this->load->database();		
			
	}
	
	public function index() {
		$current =  getdate();
		$date = $current['year'].'-'.$current['mon'].'-'.$current['mday'];
		$current_time = $current["hours"] + $current["minutes"]/60;
		
		
		// SMS
		$sms_sql = "SELECT rooms.name AS room_name, buildings.created_by AS user_id FROM appliances, switches, rooms, floors, buildings WHERE 
		rooms.notify = 1 AND appliances.name != '' AND TIMEDIFF(NOW(), update_at) > '00:00:15' AND TIMEDIFF(NOW(), update_at) <= '00:01:15' 
		AND
		appliances.switch_id = switches.id AND switches.room_id = rooms.id AND rooms.floor_id = floors.id AND floors.building_id = buildings.id 
		GROUP BY rooms.id
		";
		
		$sms_query =  $this->db->query($sms_sql);
		if ($sms_query->num_rows() > 0) {
			$data = array();
			$user_ids = array();
			foreach($sms_query->result() as $row) {
				$data[$row->user_id][] = $row->room_name;
				$user_ids[$row->user_id] = "";
			}
			$tel_sql = "SELECT id, tel FROM users WHERE users.id IN (".implode(",", array_keys($user_ids)).") AND tel != '' ";
			$tel_query =  $this->db->query($tel_sql);
			foreach($tel_query->result() as $tel_row) {
				$user_ids[$tel_row->id] = $tel_row->tel;
			}
			foreach($user_ids as $key => $tel) {
				$room_name_string = implode(", ", $data[$key]);
				$message = "The switches in the following rooms are unreachable: ".$room_name_string;
				// insert into logs
				$this->db->insert('logs', array("user_id" => $key, "content" => $message));
				
				// sms
				if (!empty($tel)) {
					$tel = urlencode($tel);
					$message = urlencode($message);
					$senty_return = file_get_contents('https://sent.ly/command/sendsms?username=nhudinhtuan@gmail.com&password=2943026&to='.$tel.'&text='.$message);
				}
			}
		}
		
		
		
		
		// check (room type = 1 & no booking) OR (room type = 0  and apply rule)
		$room_id_sql = "SELECT rooms.id AS id FROM rooms WHERE 
		
		(rooms.type = '1' AND NOT EXISTS (SELECT 1 FROM booking WHERE  room_id = rooms.id AND date = '".$date."' AND start_at <= ".$current_time." AND end_at >= ".$current_time.")) OR 
		(
		rooms.type = '0' AND EXISTS (SELECT 1 FROM rule_room, rule_details WHERE rule_room.rule_id = rule_details.rule_id AND rule_room.room_id = rooms.id AND start_at <= ".$current_time." AND end_at >= ".$current_time.")
		)
		";
		$room_id_query =  $this->db->query($room_id_sql);
		if ($room_id_query->num_rows() > 0) {
			$room_ids = array();
			foreach($room_id_query->result() as $row) {
				$room_ids[] = $row->id;
			}
			$room_ids_string = implode(",", $room_ids);
			$switch_sql = "SELECT * FROM switches LEFT JOIN appliances ON switches.id = appliances.switch_id WHERE switches.room_id IN (".$room_ids_string.") AND TIMEDIFF(NOW(), update_at) < '00:00:15' AND appliances.name !='' AND current_status = '1' ";
			$switch_query =  $this->db->query($switch_sql);
			
			foreach($switch_query->result() as $row) {
				$this->db->insert('actions', array("user_id" => '0', "switch_id" => $row->switch_id, "port_id" => $row->port_id, "status" => '0')); 
			}		
		}
		
	}
	
	
	
}
