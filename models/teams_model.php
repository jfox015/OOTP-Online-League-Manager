<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: Teams_model
	
	A class for interacting with OOTP team data in the database.
	
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
class Teams_model extends Base_ootp_model {

	protected $table		= 'teams';
	protected $key			= 'team_id';
	protected $soft_deletes	= false;
	protected $set_created	= false;
	protected $set_modified = false;
	var 	  $league_id	= 100;
	
	//---------------------------------------------------------------
	
	/**
	 *	GET OWNER COUNT.
	 *	Counts the number of team owners assigned int he database
	 *	@return				Array of DB Result Objects
	 *
	 */
	public function get_owner_count() {
        $this->db->where('league_id',$this->league_id);
		$count = $this->db->count_all_results('teams_owners');
        return $count;
	}
	/**
	 *	GET TEAMS.
	 *	A backwards compatible fetch all teams using a given league ID and 
	 *	returing the result set as an DB result() object.
	 *	@return				Array of DB Result Objects
	 *
	 */
	public function get_teams() {
		
		$teams = array();
        $this->db->dbprefix = '';
        $query = $this->db->select('team_id,abbr,name,nickname,logo_file')
						  ->where('allstar_team',0)
						  ->order_by('name,nickname','asc');
		$teams = $this->find_all_by('league_id',$this->league_id);
        $this->db->dbprefix = $this->dbprefix;
        return $teams;
	}
	/**
	 *	GET TEAMS ARRAY.
	 *	A backwards compatible fetch all teams using a given league ID and 
	 *	returing the result set as an array of values.
	 *	@return				Array of team values
	 *
	 */
	public function get_teams_array() {

        $teams = array();
		$teams_result = $this->get_teams($this->league_id);
		if (isset($teams_result) && is_array($teams_result) && sizeof($teams_result) > 0) {
			foreach($teams_result as $row) {
				$teams = $teams + array($row->team_id=>array('team_id'=>$row->team_id,'abbr'=>$row->abbr,'name'=>$row->name,
									    'nickname'=>$row->nickname,'logo_file'=>$row->logo_file));
			}
		}
		return $teams;
	}

    public function get_team_city($team_id = false) {
        if ($team_id === false) return false;
        $city = '';
        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = "";
        $query = $this->db->select('cities.name')
            ->join('cities',"cities.city_id = teams.city_id","left")
            ->where('team_id',$team_id)
            ->get('teams');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $city = $row->name;
        }
        $query->free_result();
        $this->db->dbprefix = $oldPrefix;
        return $city;
    }

    public function get_team_information($team_id = false, $fields = false) {

        if ($team_id === false || ($fields === false || !is_array($fields) || sizeof($fields) < 1)) return false;

        $info = array();
        $this->db->dbprefix = '';
        $select = '';
        foreach($fields as $field) {
            if (!empty($select)) { $select .= ","; }
            $select .= $field;
        }
        if (!empty($select)) {
            $query = $this->db->select($select)
                ->where('team_id',$team_id)
                ->get('teams');
            if ($query->num_rows() > 0) {
                $info = $query->row_array();
            }
            $query->free_result();
        }
        $this->db->dbprefix = $this->dbprefix;
        return $info;
    }

}