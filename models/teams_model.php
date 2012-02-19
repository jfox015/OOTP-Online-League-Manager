<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	TEAM MODEL CLASS.
 *
 *	@author			Jeff Fox <jfox015 (at) gmail (dot) com>
 *  @copyright   	(c)2009-12 Jeff Fox/Aeolian Digital Studios
 *	@version		1.0
 *
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
		return $this->db->count_all_results('teams_owners');
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
		$query = $this->db->select('team_id,abbr,name,nickname,logo_file')
						  ->where('allstar_team',0)
						  ->order_by('name,nickname','asc');
		$teams = $this->find_all_by('league_id',$this->league_id);
		
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
        return $info;
    }

}