<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *	LEAGUE EVENTSMODEL CLASS.
 *
 *	@author			Jeff Fox <jfox015 (at) gmail (dot) com>
 *  @copyright   	(c)2009-12 Jeff Fox/Aeolian Digital Studios
 *	@version		1.0
 *
*/
require_once(dirname(dirname(__FILE__)).'/models/base_ootp_model.php');
class Leagues_events_model extends Base_ootp_model {

	protected $table		= 'leagues_events';
	protected $key			= 'event_id';
	protected $soft_deletes	= false;
	protected $date_format	= 'datetime';
	protected $set_created	= false;
	protected $set_modified = false;
	
	/*--------------------------------------------------
	/
	/	PUBLIC FUNCTIONS
	/
	/-------------------------------------------------*/
	/**
	 *	Returns an array of upcoming league events.
	 *	@param	$limit	
	 *	@return	Array
	 */
	public function get_events($league_id = 100, $start_date = false, $limit = 3) {
		$events = array();
		if ($this->db->table_exists($this->table)) {
            $this->db->dbprefix = '';
            $this->db->select('event_id,start_date,name');
			$this->db->from($this->table);
			$this->db->where('league_id',$league_id);
			if ($start_date !== false) {
				$this->db->where('start_date >',$start_date);
			}
			$this->db->not_like('name','Announcement');
			$this->db->order_by('start_date','asc');
			$this->db->limit($limit);
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row) {
					array_push($events,array('event_id'=>$row->event_id,'name'=>$row->name,'start_date'=>$row->start_date));
				}
			}
			$query->free_result();
            $this->db->dbprefix = $this->dbprefix;
		} else {
			$this->error = 'Required database table "league_events" has not been loaded. No events could be displayed at this time.';
		}
		return $events;
	}
	/*---------------------------------------
	/	PRIVATE/PROTECTED FUNCTIONS
	/--------------------------------------*/

}