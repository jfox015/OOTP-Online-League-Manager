<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Teams_owners_model
	
	A class for mapping ownershipof OOTP teams to site members profiles.
	
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
class Teams_owners_model extends Base_ootp_model {

	protected $table		= 'teams_owners';
	protected $key			= 'id';
	protected $soft_deletes	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	protected $league_id	= 100;
	protected $use_prefix 	= true;
	protected $dbprefix 	= '';
	
	//---------------------------------------------------------------
	
	/**
	 *	C'TOR.
	 *	Builds a new instance of Team_owners_model
	 */
	public function __construct()
	{
		parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
		$this->use_prefix = ($this->settings_lib->item('osp.use_db_prefix') == 1) ? true : false;
	}
	
	//---------------------------------------------------------------
	
	/**
	 *	GET OWNER COUNT.
	 *	Counts the number of team owners assigned int he database
	 *	@param		$league_id	int 	League ID int
	 *	@return					int		owner Count
	 *
	 */
	public function get_owner_count($league_id = false) {
		if ($league_id === false)
		{
			$league_id = $this->league_id;
		}
        return $this->db->where('league_id',$league_id)
					    ->count_all_results($this->table);
	}
	/**
	 *	GET TEAM OWNERS LIST.
	 *	Returns all teams and associated team owner assigned in the database
	 *	@param		$league_id	int 	League ID int
	 *	@return					array	Array of DB Result Objects
	 *
	 */
	public function get_team_owner_list($league_id = false) {
        
		if ($league_id === false)
		{
			$league_id = $this->league_id;
		}
        $team_owner_list = array();
        if (!$this->use_prefix) $this->db->dbprefix = '';
        if ($this->db->table_exists('teams')) {
            $this->db->select('teams.team_id, teams.name, teams.nickname, teams.logo_file, user_id')
                 ->join($this->dbprefix.$this->table,$this->dbprefix.$this->table.'.team_id = teams.team_id', 'left outer')
                 ->where('teams.league_id',$league_id)
                 ->where('teams.allstar_team',0)
                 ->where('teams.level',1);
            $query = $this->db->get('teams');
			//echo($this->db->last_query()."<br />");
			if ($query->num_rows() > 0) {
				$team_owner_list = $query->result();
			}
        }
        if (!$this->use_prefix) $this->db->dbprefix = $this->dbprefix;
        return $team_owner_list;
	}
	/**
	 *	SET TEAM OWNER.
	 *	Counts the number of team owners assigned int he database
	 *	@param		$team_id	Team Int ID
	 *	@param		$user_id	User Int ID
	 *	@param		$league_id	League Int ID
	 *	@return					TRUE if set, FALSE on error
	 *
	 */
	public function set_team_owner($team_id = false, $user_id = false, $league_id = false)
	{
		if ($team_id === false)
		{
			$this->error = "No team ID was specified.";
			return false;
		}
		if ($user_id === false)
		{
			$this->error = "No team owner ID was specified.";
			return false;
		}
		if ($league_id === false)
		{
			$league_id = $this->league_id;
		}
        $prev = $this->db->where('team_id',$team_id)->where('league_id',$league_id)->count_all_results($this->table);
        if ($prev == 0)
        {
			$this->db->insert($this->table, array('league_id'=>$league_id, 'team_id'=>$team_id, 'user_id'=>$user_id));
		}
        else
        {
            $this->db->where('league_id',$league_id)->where('team_id',$team_id);
			$this->db->update($this->table,array('user_id'=>$user_id));
        }
		return true;
	}
	/**
	 *	DELETE TEAM OWNER.
	 *	Removes an entry int he database for the particular team
	 *	@param		$team_id	Team Int ID
	 *	@param		$league_id	League Int ID
	 *	@return					TRUE if deletex, FALSE on error
	 *
	 */
	public function delete_team_owner($team_id = false, $league_id = false)
	{
		if ($team_id === false)
		{
			$this->error = "no team ID was specified.";
			return false;
		}
		if ($league_id === false)
		{
			$league_id = $this->league_id;
		}
        $prev = $this->db->where('team_id',$team_id)->where('league_id',$league_id)->count_all_results($this->table);
        if ($prev > 0)
         {
            $this->db->where('league_id',$league_id)->where('team_id',$team_id);
			$this->db->delete($this->table);
        }
		return true;
	}
	
	//---------------------------------------------------------------
		
	public function create_user($email='', $activation = 1, $display_name = '', $username='', $role =  4) {
		$data = array(
			'role_id'	=> $role,
			'email'		=> $email,
			'username'	=> $username,
			'display_name'	=> $display_name,
			'banned'	=> 0,
			'ban_message'	=> '',
			'active'	=> ($activation == 1 ? 1 : 0),
            'activate_hash' => ($activation == 1 ? '' :do_hash(random_string('alnum', 40) . time()))
		);
		
		$pw = substr(md5($email.time()),0,12);
		list($password, $salt) = $this->hash_password($pw);
		
		$data['password_hash'] = $password;
		$data['salt'] = $salt;
		
		if ($this->db->insert('users', $data) == false)
		{
			$this->errors = lang('in_db_account_error');
			return false;
		}
		else 
        {
			return array('user_id'=>$this->db->insert_id(), 'password'=>$pw);
		}
	}
	
	/*---------------------------------------
	/	PRIVATE/PROTECTED FUNCTIONS
	/--------------------------------------*/
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