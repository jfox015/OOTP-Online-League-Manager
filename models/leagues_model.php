<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	LEAGUE MODEL CLASS.
 *
 *	@author			Jeff Fox <jfox015 (at) gmail (dot) com>
 *  @copyright   	(c)2009-12 Jeff Fox/Aeolian Digital Studios
 *	@version		1.0
 *
*/
require_once(dirname(dirname(__FILE__)).'/models/base_ootp_model.php');
class Leagues_model extends Base_ootp_model {

	protected $table		= 'leagues';
	protected $key			= 'league_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_created	= false;
	protected $set_modified = false;
	
	/*--------------------------------------------------
	/
	/	PUBLIC FUNCTIONS
	/
	/-------------------------------------------------*/
    public function find($id) {
        $this->db->dbprefix = '';
        $league = parent::find($id);
        $this->db->dbprefix = $this->dbprefix;
        return $league;
    }

    public function find_all($id) {
        $this->db->dbprefix = '';
        $league = parent::find_all($id);
        $this->db->dbprefix = $this->dbprefix;
        return $league;
    }
    public function find_by($field = '', $value='', $type='') {
        $this->db->dbprefix = '';
        $league = parent::find_by($field, $value, $type);
        $this->db->dbprefix = $this->dbprefix;
        return $league;
    }
    public function find_all_by($field = '', $value='') {
        $this->db->dbprefix = '';
        $league = parent::find_all_by($field, $value);
        $this->db->dbprefix = $this->dbprefix;
        return $league;
    }
    /**
	 *	In Season.
	 *	Returns a list of public leagues.
	 *	@param	$league_id	Defaults to 100
	 *	@return	TRUE or FALSE
	 */
	public function in_season($league_id = 100) {
		
		$league = $this->find_all_by('league_id',$league_id);
		
		if (isset($league) && is_array($league) && count($league)) {
			if ($league->league_state > 1 && $league->league_state < 4) {
				return true;
			} else {
				return false;
			}
		} else {
			return 'Required OOTP database tables have not been loaded.';
		}
	}
	/**
	 *	Returns a string with the state of the league.
	 *	@return	String
	 */
	public function get_league_state($league_id = 100) {
		
		$state = '';
		
		$league = $this->find_all_by('league_id',$league_id);
		
		if (isset($league) && is_array($league) && count($league)) {
			switch ($league->league_state) {
				case 4:
					$state = "Off Season";
					break;
				case 3:
					$state = "Playoffs";
					break;
				case 2:
					$state = "Regular Season";
					break;
				case 1:
					$state = "Spring Training";
					break;
				case 0:
					$state = "Preseason";
					break;
			}
		} else {
			$state = 'Required OOTP database tables have not been loaded.';
		}
		return $state;
	}
	/*---------------------------------------
	/	PRIVATE/PROTECTED FUNCTIONS
	/--------------------------------------*/

}