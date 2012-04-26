<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Human_managers_model

	Deals with human team owner information from OOTP games for online leagues.
	
	Copyright (c) 2012 Jeff Fox.

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/
require_once(dirname(dirname(__FILE__)).'/models/base_ootp_model.php');
class Human_managers_model extends Base_ootp_model {

	protected $table		= 'human_managers';
	protected $key			= 'human_manager_id';
	protected $soft_deletes	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	
	/*--------------------------------------------------
	/
	/	PUBLIC FUNCTIONS
	/
	/-------------------------------------------------*/
	
	public function find_all_by($field=NULL, $value=NULL)
	{
		$this->join('teams','teams.team_id = '.$this->table.'.team_id');
		$this->select('human_manager_id, first_name, last_name, teams.team_id, teams.name as team_name, teams.nickname as teams_nick');
		return parent::find_all_by($field, $value);
	}
	
	public function get_owner_user_matches($league_id = 100, $exclusions = false) {
		
		$userMatches = array();
		if (!isset($this->users_model)) {
			$this->load->model('users_model');
		}
		$users = $this->users_model->find_all();
		
		$this->select("first_name, last_name, teams.team_id, teams.name, teams.nickname")
			 ->join('teams','teams.team_id = '.$this->table.'.team_id','left')
			 ->where($this->table.'.team_id <> 0');
		$human_managers = $this->find_all_by($this->table.'.league_id',$league_id);
		
		if (count($human_managers) > 0) {
			foreach ($human_managers as $row) {
				 foreach($users as $user) {
					if ((isset($user->first_name) && !empty($user->first_name) && $row->first_name == $user->first_name) &&
					(isset($user->last_name) && !empty($user->last_name) && $row->last_name == $user->last_name))
					{
						array_push($userMatches, array('human_manager_id'=>$row->id, 'first_name'=>$row->first_name, 'last_name'=>$row->last_name, 'user_id'=>$user->id,
						'username'=>$user->username);
					}
				}
			}
		}
		$this->$query->free_result();
		return $userMatches;
	}
	public create_users_from_human_managers() {
		
		$human_manager_ids = $this->input->post('human_manager_ids');
		
		if (isset($human_manager_ids) && is_array($human_manager_ids) && count($human_manager_ids) > 0) {
			
			$id_str = "(";
			foreach ($human_manager_ids as $id) 
			{
				if ($id_str != "(") { $id_str .= ","; }
				$id_str .= $email
			}
			$id_str = ")";
			$this->db->where_in('human_manager_id',$id_str);
			$managers = $this->select('first_name, last_name')->find_all();
			
			foreach ($managers as $manager) 
			{
				$username = $manager->first_name." ".$manager->last_name;
				$email = ($this->input->post($manager->human_manager_id)) ? $this->input->post($manager->human_manager_id) : '';
				if ($this->create_user($username, $email)) {
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
			$this->error = "No managers were found.";
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