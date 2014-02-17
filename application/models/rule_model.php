<?
ini_set('date.timezone', 'Asia/Singapore');
class Slot {
	private static $day_string = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	var $id;
	var $rule_id;
	var $day;
	var $start_at;
	var $end_at;
	
	function getDay() {
		return Slot::$day_string[$this->day];
	}
	
	function getStartTime() {
		$endString = intval($this->start_at*60)%60;
		if ($endString < 10)
			$endString = "0".$endString;
		return (intval($this->start_at)).":".$endString;
	}
	
	function getEndTime() {
		$endString = intval($this->end_at*60)%60;
		if ($endString < 10)
			$endString = "0".$endString;
		return (intval($this->end_at)).":".$endString;
	}
	
	function add($slot) {
		if ($slot->start_at > $this->end_at || $slot->end_at < $this->start_at) {
			return 0;
		}
		
		$this->start_at = ($this->start_at < $slot->start_at)?$this->start_at:$slot->start_at;
		$this->end_at = ($this->end_at > $slot->end_at)?$this->end_at:$slot->end_at;
		return 1;
	}
}

class Rule {
	var $id;
	var $name;
	var $office_id;
	var $create_at;
	var $slots = array();
	
	function addSlot($slots) {
		foreach($slots as $slot) {
			$this->slots[$slot->day][] = $slot;
		}
	}
}


class Room {
	var $id;
	var $name;
	var $description;
	var $type;
}

class Rule_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	
	function getRuleByOfficeId($office_id) {
		$rule_query = $this->db->get_where('rule_info', array('office_id' => $office_id));
		$rules = $rule_query->result('Rule');
		foreach($rules as $key => $rule) {
			$slot_info = 	$this->db->get_where('rule_details', array('rule_id' => $rule->id));
			$slots = $slot_info->result('Slot');
			$rules[$key]->addSlot($slots);
		}
		return $rules;
	}
	
	function getRule($rule_id) {
		$rule_query = $this->db->get_where('rule_info', array('id' => $rule_id));
		$rule = $rule_query->first_row('Rule');
		
		$slot_info = 	$this->db->get_where('rule_details', array('rule_id' => $rule->id));
		$slots = $slot_info->result('Slot');
		$rule->addSlot($slots);
		
		return $rule;
	}
	
	function getRuleByRoomId($room_id) {
		$sql = "SELECT rule_info.id AS id, rule_info.name, rule_info.description FROM rule_room, rule_info WHERE rule_room.rule_id = rule_info.id AND rule_room.room_id = ".$room_id;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$rule = $query->first_row('Rule');
			
			$slot_info = 	$this->db->get_where('rule_details', array('rule_id' => $rule->id));
			$slots = $slot_info->result('Slot');
			$rule->addSlot($slots);
			
			return $rule;
		} else {
			return NULL;
		}
	}
}

?>