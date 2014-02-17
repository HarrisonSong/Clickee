<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->library('session');			
		$this->load->helper('url');
		$this->load->database();		
			
		define('ASSEST_URL', base_url().'assets/');	
		// check permission
		if($this->session->userdata('logged_in') == TRUE) {
			$this->session->set_userdata('redirect_link', current_url());
			redirect(site_url("desktop/user/index"));
			return;
		}								
	}
	
	public function index() {
		$this->load->view('version2/login.php', $data);
	}
	
	public function createAccount() {
		$this->load->view('desktop/CreateAccount.php', $data);
	}
	
	public function remote_login($sv) {
		$redirect_back_link = site_url("desktop/general/remote_login_verify/".$sv);
		switch($sv) {
			case "fb":
				$this->load->library('facebook');
				$this->load->helper('facebook');
				$facebook = fb_object($redirect_back_link);	
				if (!is_string($facebook)) {
					redirect($redirect_back_link);
				} else {
					redirect($facebook);
				}
				break; 
			case "gmail":
			case "yahoo":
				$this->load->library('Lightopenid');
				$this->load->helper('openid');
				redirect(openid_login_link($sv, $redirect_back_link));
				break;
			default:
				redirect(site_url("desktop/general/index"));
		}
	
	}
	
	public function remote_login_verify($sv) {
		$user_openid = array("remote_source" => $sv, "remote_id" => "", "name" => "", "email" => "");
		
		switch($sv) {
			case "fb":
				$this->load->library('facebook');
				$this->load->helper('facebook');
				$facebook = fb_object("");	
				if (!is_string($facebook)) {
					$user_profile = $facebook->api('/me');
					$user_openid ["remote_id"] = $user_profile["id"];
					$user_openid ["name"] = $user_profile["name"];
					$user_openid ["email"] = $user_profile["email"];
				} else {
					redirect(site_url("desktop/general/index"));
					return;
				}
				break;
			case "gmail":
			case "yahoo":
				$this->load->library('Lightopenid');
				$this->load->helper('openid');
				$openid = openid_object();
				if ($openid->mode == "id_res") {
					$attributes = $openid->getAttributes();
					print_r($attributes);
					$user_openid ["remote_id"] = $attributes["contact/email"];
					$user_openid ["name"] = $attributes["namePerson"];
					$user_openid ["email"] = $attributes["contact/email"];
				} else {
					redirect(site_url("desktop/general/index"));
					return;
				}
				break;
			default:
				redirect(site_url("desktop/general/index"));
				return;
		}
		
		//Check remote server
		$remote_query = $this->db->get_where('remote_login', array("remote_server" => $sv, "remote_id" => $user_openid ["remote_id"]), 1);
		
		$user_id = "";
		$tel = "";
		$email = $user_openid ["email"];
		$username = $user_openid ["name"];
		$accType = "";
		if (empty($username)) {
			$username = split('@', $email);
			$username = $username[0];
		}
		
		if ($remote_query->num_rows() == 0) { //the remote server is not recorded
			$user_query = $this->db->get_where('users', array('email' => $user_openid ["email"]), 1);
			if($user_query->num_rows()>0) {
				$user_id = $user_query->first_row()->id;
				$tel = $user_query->first_row()->tel;
				$username = $user_query->first_row()->username;
				$accType = $user_query->first_row()->type;
				$this->db->insert('remote_login', array("user_id" => $user_id, "remote_server" => $sv, "remote_id" => $user_openid ["remote_id"])); 
			} else {
				$this->db->insert('users', array("username" => $username, "email" => $user_openid ["email"], "password" => "", "tel" => ""));
				$user_id = $this->db->insert_id();
				$this->db->insert('remote_login', array("user_id" => $user_id, "remote_server" => $sv, "remote_id" => $user_openid ["remote_id"])); 
			}
		} else {
			$user_id = $remote_query->first_row()->user_id;
			$user_query = $this->db->get_where('users', array('id' => $user_id), 1);
			$tel = $user_query->first_row()->tel;
			$username = $user_query->first_row()->username;
			$email = $user_query->first_row()->email;
			$accType = $user_query->first_row()->type;
		}
		
		// set session
		$this->session->set_userdata(array("logged_in" => TRUE,  "userid" => $user_id, "username" => $username, "email" => $email, "tel" => $tel, "acct_type" => $accType)); 
		
		redirect(site_url("desktop/user/index"));
		return;
	}
	
	
	
	
	/* AJAX */
	public function login() {
		
		if($this->session->userdata('logged_in') == TRUE) {
			echo "You have already logged in!";
			return;
		}
		
		$email = $this->input->post('loginEmail');
		$password =  $this->input->post('loginPassword');
		
		if (empty($email) || empty($password)) {
			echo "The required fields are empty";
			return;
		}
		
		$query = $this->db->get_where('users', array('email' => $email, 'password' => $password), 1);
		if ($query->num_rows() > 0) {
			echo "success";
			
			$row = $query->first_row();
			
			// set session
			$this->session->set_userdata(
				array("logged_in" => TRUE,  "userid" => $row->id, "username" => $row->username, "email" => $row->email, "tel" => $row->tel,  "acct_type" =>  $row->type)
			); 
		} else {
			echo "Email or Password is Incorrect!";
		}
		
	}
	
	//AJAX
	public function signup() {
		$username = $this->input->post('signupUsername');
		$email = $this->input->post('signupEmail');
		$password = $this->input->post('signupPassword');
		$phonenumber = $this->input->post('phone_number');
		
		if (empty($username) || empty($email) || empty($password)) {
			echo "The required fields are empty";
			return;
		}
		
		// check whether email address is exist.
		$query = $this->db->get_where('users', array('email' => $email), 1);
		if ($query->num_rows() > 0) {
			echo " there is an existing account associated with my email address.";
			return;
		}
		
		$this->db->insert('users', array("username" => $username, "email" => $email, "password" => $password, "tel" => $phonenumber));
		$user_id = $this->db->insert_id();
		
		// set session
		$this->session->set_userdata(array("logged_in" => TRUE,  "userid" => $user_id, "username" => $username, "email" => $email, "tel" => $phonenumber, "acct_type" => "")); 
		echo "success";
	}
	
	
	public function aboutProduct() {
		$this->load->view('version2/aboutProduct.php', $data);
	}
	
}
