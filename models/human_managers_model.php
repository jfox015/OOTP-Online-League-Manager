<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	HUMAN MANAGERS MODEL CLASS.
 *
 *	@author			Jeff Fox <jfox015 (at) gmail (dot) com>
 *  @copyright   	(c)2012 Jeff Fox/Aeolian Digital Studios
 *	@version		1.0
 *
*/
require_once(dirname(dirname(__FILE__)).'/models/base_ootp_model.php');
class Human_managers_model extends Base_ootp_model {

	protected $table		= 'human_managers';
	protected $key			= 'email';
	protected $soft_deletes	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	
	/*--------------------------------------------------
	/
	/	PUBLIC FUNCTIONS
	/
	/-------------------------------------------------*/
	public function get_owner_users_matches($league_id = 100, $exclusions = false) {
		
		$userMatches = array();
		if (!isset($this->users_model)) {
			$this->load->model('users_model');
		}
		$users = $this->users_model->find_all();
		
		$this->select("email, teams.team_id, teams.team_name, teams.nick_name");
		$exclud_str = "(";
		if ($exclusions !== false && is_array($exclusions) && count($exclusions)) {
			foreach ($exclusions as $email) {
				if ($exclud_str != "(") { $exclud_str .= ","; }
				$exclud_str .= $email;
			}
		}
		$exclud_str = ")";
		if ($exclud_str != "()") {
			$this->db->where_not_in('email',$exclud_str);
		}
		$this->db->join('teams','teams.team_id = '.$this->table.'.team_id','left');
		$query = $this->find_all_by('teams.league_id',$league_id);
		
		if ($query->num_rows() > 0) {
			foreach ($query->result as $row) {
				 foreach($users as $user) {
					if ($user->email ==$row->email) {
						array_push($userMatches, array('username'=>$user->username,'email'=>$user->email,'team_id'=>$row->team_id,
														'team'=>$row->team_name." ".$row->nick_name));
					}
				}
			}
		}
		$this->$query->free_result();
		return $userMatches;
	}
	public create_users_from_owners($owners = false) {
		if ($owners !== false && is_array($owners) && count($owners)) {
			$email_str = "(";
			if ($owners !== false && is_array($owners) && count($owners)) {
				foreach ($owners as $email) {
					if ($email_str != "(") { $email_str .= ","; }
					$email_str .= $email;
				}
			}
			$email_str = ")";
			if ($email_str != "()") {
				$this->db->where_in('email',$email_str);
			}
			$query = $this->find_all();
			if ($query->num_rows() > 0) {
			foreach ($query->result as $row) {
				$username = explode("@",$row->email);
				if ($this->create_user($username, $row->email)) {
					$user_id = $this->db->insert_id();
					$data = array(
						'league_id'	=> $row->league_id,
						'team_id'	=> $row->league_id,
						'user_id'	=> $user_id
					);
					$this->db->insert('team_owners', $data) == false)
					{
						$this->errors = lang('own_map_id_error');
						return false;
					}
				}
			}
		} else {
			$this->error = "No owners were received.";
			return false;
		}
		return true;
	}
	/*---------------------------------------
	/	PRIVATE/PROTECTED FUNCTIONS
	/--------------------------------------*/
	private function create_user($username='', $email='', $role =  5, $active = 1) {
		$data = array(
			'role_id'	=> $role,
			'email'		=> $email,
			'username'	=> $username,
			'active'	=> $active,
		);
		list($password, $salt) = $this->hash_password($this->input->post('password'));
		
		$data['password_hash'] = $password;
		$data['salt'] = $salt;
		
		if ($this->db->insert('users', $data) == false)
		{
			$this->errors = lang('in_db_account_error');
			return false;
		}
	}
	/*
		Method: hash_password()
		
		Generates a new salt and password hash for the given password.
		
		Parameters:
			$old	- The password to hash.
			
		Returns:
			An array with the hashed password and new salt.
	*/
	private function hash_password($old='') 
	{
		if (!function_exists('do_hash'))
		{
			$this->load->helper('security');
		}
	
		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);
		
		return array($pass, $salt);
	}
	
	//--------------------------------------------------------------------
	
	private function generate_salt() 
	{
		if (!function_exists('random_string'))
		{
			$this->load->helper('string');
		}
		
		return random_string('alnum', 7);
	}
}