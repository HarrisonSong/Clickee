<?
ini_set('date.timezone', 'Asia/Singapore');
class Booking {
	private static $day_string = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	var $id;
	var $date;
	var $start_at;
	var $end_at;
	var $user_id;
	var $user_name;
	var $room_id;
	
	/*
	function getDay() {
		return Slot::$day_string[$this->day];
	}*/
	
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
	
}


class Booking_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getBookingInfoByRoomId($room_id, $date = NULL) {
		
		$current =  getdate();
		
		if (empty($date)) {
			$sql = "SELECT booking.id AS id, date, start_at, end_at, users.id AS user_id, users.username AS user_name, users.email AS email, room_id FROM booking, users where booking.user_id = users.id AND booking.room_id = ".$room_id." AND booking.date >= '".$current['year'].'-'.$current['mon'].'-'.$current['mday']."' ORDER BY booking.date, start_at";
		} else {
			$sql = "SELECT booking.id AS id, date, start_at, end_at, users.id AS user_id, users.username AS user_name, users.email AS email, room_id FROM booking, users where booking.user_id = users.id AND booking.room_id = ".$room_id." AND booking.date = '".$date."' ORDER BY booking.date, start_at";
		}
		$query = $this->db->query($sql);
		return $query->result('Booking');
	}
	
	
	
	function isOverlap($room_id, $date, $startTime, $endTime) {
		$sql = "SELECT 1 FROM booking WHERE room_id = ".$room_id." AND date = '".$date."' AND start_at < ".$endTime. " AND end_at > ".$startTime;
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			return 1;
		} else {
			return 0;
		}
	}
}

?>