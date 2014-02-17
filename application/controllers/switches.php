<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Switches extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// Your own constructor code
		$this->load->library('session');			
		$this->load->helper('url');
		$this->load->database();		
			
		define('ASSEST_URL', base_url().'assets/');						
	}
	
	
	/* DEVICES */
	public function dPort($switch_id, $port_id, $status) {
		
		if ($switch_id == "" || $port_id == "" || $status == "") {
			return;
		}
		
		
		// update current status
		$sql = "UPDATE appliances SET current_status = '".$status."', update_at = NOW() WHERE switch_id = '". $switch_id."' AND port_id = '".$port_id."' ";
		$this->db->query($sql);
		
		//get the expect status
		/*
		$this->db->select('expect_status');
		$query = $this->db->get_where('appliances', array('switch_id' => $switch_id, 'port_id' => $port_id), 1);
		if ($query->num_rows() > 0) {
			echo "{".$query->first_row()->expect_status."}";
		} else {
			return;
		}*/
		
		// update old actions
		$this->db->where(array('switch_id' => $switch_id, "port_id" => $port_id, "status" => $status));
		$this->db->update('actions', array("is_check" => 1)); 
		
		$action_sql = "SELECT status FROM actions WHERE switch_id = '".$switch_id."' AND port_id = ".$port_id." AND is_check = 0 ORDER BY id LIMIT 1";
		$action_query = $this->db->query($action_sql);
		if ($action_query->num_rows() > 0) {
			echo "{".$port_id.":".$action_query->first_row()->status."}";
		} else {
			echo "{".$port_id.":2}";
		}
		
		
	}
	
	public function pPort($switch_id, $port_id, $status) {
		$this->db->insert('actions', array("user_id" => "", "switch_id" => $switch_id, "port_id" => $port_id, "status" => $status)); 
		echo "success";
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */